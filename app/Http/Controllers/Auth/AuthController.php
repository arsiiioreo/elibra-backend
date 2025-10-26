<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\OTPController;
use App\Http\Requests\RegistrationRequest;
use App\Mail\EmailVerification;
use App\Models\ActivityLog;
use App\Models\OTP;
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
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Auth\OTPVerifier;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class AuthController extends Controller
{
    public function user()
    {
        $auth = auth('api')->user();

        $data = [
            // Personal Info
            'name' => $auth->last_name . ', ' . $auth->first_name . ' ' . ($auth->middle_initial ? $auth->middle_initial . '.' : ''),
            'last_name' => $auth->last_name,
            'middle_initial' => $auth->middle_initial ?? 'N/A',
            'first_name' => $auth->first_name,
            'sex' => $auth->sex,
            
            // Contact Info
            'contact_number' => $auth->contact_number,
            'email' => $auth->email,
            
            //Account Info
            'email_verified_at' => $auth->email_verified_at,
            'profile_picture' => $auth->profile_photos?->path ? asset('storage/' . $auth->profile_photos?->path) : asset('logo.png'),
            'role' => $auth->role,

            'id_number' => $auth->patron?->id_number,
            'address' => $auth->patron?->address,
            'ebc' => $auth->patron?->ebc ?? 'N/A',
        ];

        if ($auth->isAdmin) {
            $data += [

            ];

        } else if ($auth->isLibrarian) {
            $data += [
                'campus' => $auth->campus,
            ];
        } else if ($auth->isPatron) {

            // $guestType = PatronTypes::where('name', 'Guest')->first()->id;

            $data += [
                "campus" => $auth->campus,
                'patron' => $auth->patron,
                'patron_type' => $auth->patron->patron_type
            ];
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

        $validated = $request->validate([
            'token' => 'required|string',
            'otp' => 'required|string',
        ]);

        try {
            // $user = auth('api')->user();

            // if (!$user || !($user instanceof User)) {
            //     return response()->json(['status' => 'error', 'message' => 'Unauthorized.'], 401);
            // }

            $otpRecord = OTP::where('otp_code', $validated['otp'])
                ->where('otp_token', $validated['token'])
                ->where('expires_at', '>', now())
                ->first();

            // $otpRecord = OTP::where('otp_code', $otp)
            //     ->where('otp_token', $token)
            //     ->where('expires_at', '>', now())
            //     ->first();

            // dd(' token=' . $token . ' otp=' . $otp);

            Log::info('Verify Email Request', [
                'token' => $validated['token'],
                'otp' => $validated['otp'],
            ]);


            if ($otpRecord) {
                $user = User::find($otpRecord->user_id);
                $user->email_verified_at = now();
                $user->save();

                Log::info('OTP Record Found', ['record' => $otpRecord]);

                $otpRecord->delete(); // Invalidate the OTP after successful verification

                DB::commit();

                // return response()->json(['status' => 'success'], 200);

                return redirect()->away(env('FRONTEND_URL') . '/email-verified');
            } else {
                return response()->json(['status' => 'error', 'message' => 'Invalid or expired OTP.'], 400);
            }
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Something went wrong.' . $e->getMessage()], 500);
        }
    }

    public function verifyPatronEmail(Request $request)
    {
        DB::beginTransaction();
        try {
            $otpRecord = OTP::where('otp_code', $request->otp)
                ->where('otp_token', $request->code)
                ->where('expires_at', '>', now())
                ->first();

            if ($otpRecord) {
                $user = User::find($otpRecord->user_id);
                $user->email_verified_at = now();
                $user->save();

                $otpRecord->delete(); // Invalidate the OTP after successful verification
                DB::commit();

                return response()->json(['status' => 'success'], 200);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Invalid or expired OTP.'], 400);
            }
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Something went wrong: ' . $e->getMessage()], 500);
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

        if ($user && $user->login_attempt >= 5) {
            return response()->json(["message" => "Too many failed login attempts, please contact your administrator to reset your password or resolve this issue."], 403);
        }

        if ($user && auth('api')->attempt(['email' => $user->email, 'password' => request('password')])) {


            if ($user && $user->pending_registration_approval) {
                return response()->json(['status' => 'error', 'errors' => [
                    'users' => 'Account is pending for registration approval, please wait or contact the administrator to get a status update about your application.'
                ]], 403);
            }
            $token = auth('api')->login($user);

            $user->login_attempt = 0;
            $user->save();

            ActivityLog::create([
                'user_id' => auth('api')->user()->id,
                'title' => 'Login',
                'description' => 'You logged in.',
            ]);

            return $this->respondWithToken($token);
        } else {
            ActivityLog::create([
                'user_id' => $user->id,
                'title' => 'Invalid Login',
                'description' => 'Incorrect password, attempt to login failed.',
            ]);

            if ($user->role != 0) {
                $user->login_attempt += 1;

                if ($user->login_attempt == 5) {
                    $user->status = 1;
                    ActivityLog::create([
                        'user_id' => $user->id,
                        'title' => 'Account Locked',
                        'description' => 'Due to multiple login attempts, your account has been locked.',
                    ]);
                }
                $user->save();
            }

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
            $data = $request->validated();

            $data['last_name'] = ucwords(strtolower($data['last_name']));
            $data['first_name'] = ucwords(strtolower($data['first_name']));
            $data['middle_initial'] = strtoupper($data['middle_initial']);

            $user = User::create([
                'last_name' => $data['last_name'],
                'first_name' => $data['first_name'],
                'middle_initial' => $data['middle_initial'],
                'sex' => $data['sex'],
                'role' => $data['role'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);

            $user_patron = Patron::create([
                'user_id' => $user->id,
                'patron_type_id' => $request->patron_type_id,
                'status' => 'active', // Set status to inactive for Guest patrons
            ]);

            // $request->campus is an id if the user is not a guest, if the user is a guest, it is the name of the external organization

            $guestType = PatronTypes::where('name', 'Guest')->first()->id;

            if ($request->patron_type_id == $guestType) {
                $user->campus_id = null;
                $user->save();

                $user_patron->external_organization = $request->external_organization;
                $user_patron->ebc = sprintf(
                    'EBC%s%s%s',
                    Str::padLeft($user_patron->id, 3, '0'), // ensure 3-digit campus_id
                    Str::padLeft($user->id, 5, '0'),       // ensure 5-digit user_id
                    Str::upper(Str::random(5))            // random suffix for uniqueness
                );
            } else {
                $user->campus_id = $request->campus;
                $user->save();

                $user_patron->ebc = sprintf(
                    'EBC%s%s%s',
                    Str::padLeft($user->campus_id, 3, '0'), // ensure 3-digit campus_id
                    Str::padLeft($user->id, 5, '0'),        // ensure 5-digit user_id
                    Str::upper(Str::random(5))             // random suffix for uniqueness
                );
            }
            $user_patron->id_number = $request->id_number; // ID number for guest and non-guest
            $user_patron->save();

            $token = auth('api')->login($user);
            DB::commit();

            ActivityLog::create([
                'user_id' => auth('api')->user()->id,
                'title' => 'Registration',
                'description' => 'Account is created.',
            ]);

            return $this->respondWithToken($token);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function logout()
    {
        try {
            $user = auth('api')->user();
            if ($user) {
                ActivityLog::create([
                    'user_id' => $user->id,
                    'title' => 'Logout',
                    'description' => 'You logged out.',
                ]);
            }

            auth('api')->logout();
            return response()->json(['status' => 'success', 'message' => 'Successfully logged out']);
        } catch (TokenExpiredException $e) {
            // Decode token manually and invalidate
            $token = JWTAuth::getToken();
            $payload = JWTAuth::manager()->decode($token, false); // skip expiry check
            JWTAuth::invalidate($token);

            return response()->json(['status' => 'success', 'message' => 'Logged out (expired token handled)']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to logout', 'error' => $e->getMessage()]);
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
