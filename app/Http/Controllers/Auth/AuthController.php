<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\OTPController;
use App\Http\Requests\RegistrationRequest;
use App\Mail\EmailVerification;
use App\Models\Patron;
use App\Models\PatronTypes;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function user()
    {
        $auth = auth('api')->user();

        // Default null values
        $campus = null;
        $branch = null;

        if ($auth->isAdmin) {
            $campus = ['name' => "Global"];
            $branch = ['name' => "University Libary"];
        } else if ($auth->isLibrarian) {
            $campus = ['name' => "Echague Campus"];
        }

        $pfp = $auth->profile_photos?->path ? asset('storage/' . $auth->profile_photos?->path) : asset('logo.png');

        $data = [
            'name' => $auth->last_name . ', ' . $auth->first_name . ' ' . ($auth->middle_initial ? $auth->middle_initial . '.' : 'N/A'),
            'last_name' => $auth->last_name,
            'middle_initial' => $auth->middle_initial ?? 'N/A',
            'first_name' => $auth->first_name,
            'sex' => $auth->sex,
            'email' => $auth->email,
            'email_verified_at' => $auth->email_verified_at,
            'profile_picture' => $pfp,
            'campus' => $campus,
        ];

        // // Conditional append
        if ($branch) {
            $data['branch'] = $branch;
        }

        return response()->json($data);
    }


    public function refresh()
    {
        /** @var \Tymon\JWTAuth\JWTGuard $auth */
        $auth = auth('api');

        try {
            return response()->json([
                'status'       => 'success',
                'access_token' => $auth->refresh(),
                'token_type'   => 'bearer',
            ]);
        } catch (JWTException $e) {
            throw $e;
            return response()->json(['error' => 'Token invalid or expired'], 401);
        }
    }

    // Verify Email
    public function verifyEmail(Request $request)
    {
        DB::beginTransaction();

        try {
            $user = auth('api')->user();
            OTPController::generateOTP($user->id);

            if (!$user || !($user instanceof User)) {
                return response()->json(['status' => 'error', 'message' => 'Unauthorized.'], 401);
            }

            // Delegate OTP verification to OTPVerifier
            if (OTPVerifier::verifyOTP($request->otp)) {
                $user->email_verified_at = now();
                $user->save();

                DB::commit();
                return response()->json(['status' => 'success'], 200);
            }

            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Invalid or expired OTP.'], 400);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Something went wrong.'], 500);
        }
    }


    // Login
    public function login()
    {
        $user = User::where('email', request('user'))->first() ??
            Patron::where('id_number', request('user'))->first()?->user;

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'errors' => [
                    'users' => 'User not found.'
                ]
            ], 404);
        }

        if ($user && auth('api')->attempt(['email' => $user->email, 'password' => request('password')])) {
            if ($user && $user->pending_registration_approval) {
                return response()->json(['status' => 'error', 'errors' => [
                    'users' => 'Account is pending for registration approval, please wait or contact the administrator to get a status update about your application.'
                ]], 403);
            }
            $token = auth('api')->login($user);
            return $this->respondWithToken($token);
        } else {
            return response()->json(['status' => 'error', 'errors' => [
                'users' => 'Incorrect password, please try again.'
            ]], 401);
        }
    }

    // Registration API for Patrons
    public function register(RegistrationRequest $request)
    {
        DB::beginTransaction();

        try {
            $user = User::create($request->only(['last_name', 'first_name', 'middle_initial', 'sex', 'role', 'email', 'password',]));
            $user_patron = Patron::create([
                'user_id' => $user->id,
                'patron_type_id' => $request->patron_type,
                'status' => 'active', // Set status to inactive for Guest patrons
            ]);

            // $request->campus is an id if the user is not a guest, if the user is a guest, it is the name of the external organization

            $guestType = PatronTypes::where('name', 'Guest')->first()->id;

            if ($request->patron_type == $guestType) {
                $user_patron->external_organization = $request->campus;
                $user_patron->ebc = sprintf(
                    'EBC%s%s%s',
                    Str::padLeft($user_patron->id, 3, '0'), // ensure 3-digit campus_id
                    Str::padLeft($user->id, 5, '0'),        // ensure 5-digit user_id
                    Str::upper(Str::random(5))              // random suffix for uniqueness
                );
            } else {
                $user_patron->campus_id = $request->campus;
                $user_patron->ebc = sprintf(
                    'EBC%s%s%s',
                    Str::padLeft($user_patron->campus_id, 3, '0'), // ensure 3-digit campus_id
                    Str::padLeft($user->id, 5, '0'),        // ensure 5-digit user_id
                    Str::upper(Str::random(5))              // random suffix for uniqueness
                );
                $user_patron->id_number = $request->id_number;
            }
            $user_patron->save();

            $token = auth('api')->login($user);

            DB::commit();

            return $this->respondWithToken($token);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // Response with Token
    public function respondWithToken($token)
    {
        return response()->json([
            'status' => 'success',
            'access_token' => $token,
            'token_type' => 'bearer',
        ]);
    }
}
