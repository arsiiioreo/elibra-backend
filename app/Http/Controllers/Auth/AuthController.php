<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationRequest;
use App\Mail\EmailVerification;
use App\Models\OTP;
use App\Models\Patron;
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
    public function refresh()
    {
        try {
            $newToken = JWTAuth::parseToken()->refresh();

            return response()->json([
                'status'       => 'success',
                'access_token' => $newToken,
                'token_type'   => 'bearer',
            ]);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token invalid or expired'], 401);
        }
    }

    // Verify Email
    public function verifyEmail(Request $request)
    {
        DB::beginTransaction();

        try {
            $user = auth('api')->user();

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

        if ($user && auth('api')->attempt(['email' => $user->email, 'password' => request('password')])) {
            if ($user && $user->pending_registration_approval) {
                return response()->json(['status' => 'error', 'message' => "Account is pending for approval."], 403);
            }
            $token = auth('api')->login($user); 
            return $this->respondWithToken($token);
        } else {
            return response()->json(['status' => 'error', 'message' => "Invalid Credentials."], 401);
        }
    }

    // Register
    public function register(RegistrationRequest $request)
    {
        DB::beginTransaction();

        try {
            $user = User::create($request->only([
                'name',
                'sex',
                'campus_id',
                'role',
                'email',
                'password',
            ]));

            $otp = OTP::create([
                'user_id' => $user->id,
                'otp_code' => rand(100000, 999999),
                'otp_token' => Str::uuid(),
                'expires_at' => now()->addHour(),
            ]);

            $user->code = $otp->otp_token;
            $user->save();

            Mail::to($user->email)->send(new EmailVerification($user, $otp->otp_code));
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
