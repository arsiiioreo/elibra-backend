<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OTP;
use Illuminate\Http\Request;

class OTPVerifier extends Controller
{
    public function verifyEmail(Request $request) {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $user = auth('api')->user();
        
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized.'], 401);
        }

        $otpRecord = OTP::where('user_id', $user->id)
                                    ->where('otp_code', $request->otp)
                                    ->where('expires_at', '>', now())
                                    ->first();

        if ($otpRecord) {
            $user->email_verified_at = now();
            $user->save();
            $otpRecord->delete(); // Invalidate the OTP after successful verification
            return response()->json('success');
            // return response()->json(['status' => 'success', 'message' => 'Email verified successfully.']);
        } else {
            // return response()->json(['status' => 'error', 'message' => 'Invalid or expired OTP.'], 400);
            return response()->json('failed');
        }

    }
}
