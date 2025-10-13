<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\EmailVerification;
use App\Models\OTP;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OTPVerifier extends Controller
{
    public static function verifyOTP($otpCode)
    {
        $user = auth('api')->user();

        $otpRecord = OTP::where('user_id', $user->id)
            ->where('otp_code', $otpCode)
            ->where('otp_token', $user->code)
            ->where('expires_at', '>', now())
            ->first();

        if ($otpRecord) {
            $otpRecord->delete(); // Invalidate the OTP after successful verification
            $user->code = null;
            if ($user instanceof User) {
                $user->save();
            }
            return true;
        } else {
            return false;
        }
    }

    public function resendOTP(Request $request)
    {
        $user = auth('api')->user();

        if (!$user || !($user instanceof User)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized.'], 401);
        }

        try {
            DB::beginTransaction();

            // Optionally delete old OTPs for a clean slate
            OTP::where('user_id', $user->id)->delete();

            // Generate a new OTP
            $newOtp = rand(100000, 999999);

            // Save to the database
            $otp = OTP::create([
                'user_id' => $user->id,
                'otp_code' => $newOtp,
                'otp_token' => Str::uuid(),
                'expires_at' => now()->addSeconds(90), // expire after 90 seconds
            ]);

            $user->code = $otp->otp_token;
            $user->save();

            // Send via email or SMS
            Mail::to($user->email)->send(new EmailVerification($user, $newOtp));

            // Commit the transaction
            DB::commit();

            return response()->json([
                'status' => 'success',
            ]);
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to resend OTP. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
