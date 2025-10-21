<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\OTPController;
use App\Http\Requests\RegistrationRequest;
use App\Mail\EmailVerification;
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
        }else if ($auth->patron && $auth->campus) {

        // $campus = [
        //     // 'id' => $auth->patron->campus->id,
        //     'name' => $auth->patron->campus->name,
        //     'abbrev' => $auth->patron->campus->abbrev ?? null,
        //     'address' => $auth->patron->campus->address ?? null,
        // ];

        // Check if patron_type is Guest (3)
            $guestType = PatronTypes::where('name', 'Guest')->first()->id;

            if ($auth->patron->patron_type_id == $guestType) {
                $externalOrganization = [
                    'name' => $auth->patron->external_organization,
                ];
            } else if ($auth->campus) {
                $campus = [
                    'name' => $auth->campus->name,
                    'abbrev' => $auth->campus->abbrev ?? null,
                    'address' => $auth->campus->address ?? null,
                ];
            }
        }
     

        $pfp = $auth->profile_photos?->path ? asset('storage/' . $auth->profile_photos?->path) : asset('logo.png');

        $profilePhoto = $auth->profile_photos ? [
            'id' => $auth->profile_photos->id,
            'path' => asset( $auth->profile_photos->path),
            'original_name' => $auth->profile_photos->original_name,
            'stored_name' => $auth->profile_photos->stored_name,
        ] : null;

        $data = [
            'name' => $auth->last_name . ', ' . $auth->first_name . ' ' . ($auth->middle_initial ? $auth->middle_initial . '.' : ''),
            'last_name' => $auth->last_name,
            'middle_initial' => $auth->middle_initial ?? 'N/A',
            'first_name' => $auth->first_name,
            'sex' => $auth->sex,
            'contact_number' => $auth->contact_number,
            'email' => $auth->email,
            'email_verified_at' => $auth->email_verified_at,
            'date_joined' => $auth->patron?->date_joined,
            'profile_picture' => $pfp,
            'profile_photo' => $profilePhoto,
            'campus' => $campus,
            'id_number' => $auth->patron?->id_number,  
            'address' => $auth->patron?->address,
            'ebc' => $auth->patron?->ebc ?? 'N/A',
            'role' => $auth->role,
            'patron_type' => $auth->patron?->patron_type_id,
        ];

        //fetch external_org for aliens
        if ($auth->patron && $auth->patron->patron_type_id == 3) {
            $data['external_organization'] = $auth->patron->external_organization ?? null;
        }

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
            $user = User::create($request->only(['last_name', 'first_name', 'middle_initial', 'sex', 'role', 'email', 'password']));
            $user_patron = Patron::create([
                'user_id' => $user->id,
                'patron_type_id' => $request->patron_type_id,
                'status' => 'active', // Set status to inactive for Guest patrons
            ]);

            // $request->campus is an id if the user is not a guest, if the user is a guest, it is the name of the external organization

            $guestType = PatronTypes::where('name', 'Guest')->first()->id;

            if ($request->patron_type_id== $guestType) {
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
