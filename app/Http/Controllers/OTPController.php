<?php

namespace App\Http\Controllers;

use App\Mail\EmailVerification;
use App\Models\OTP;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OTPController extends Controller
{
    public static function generateOTP($userId)
    {
        $user = User::find($userId);
        $otp = OTP::create([
            'user_id' => $userId,
            'otp_code' => rand(100000, 999999),
            'otp_token' => Str::uuid(),
            'expires_at' => now()->addHour(),
        ]);

        $user->code = $otp->otp_token;
        $user->save();

        Mail::to($user->email)->send(new EmailVerification($user, $otp->otp_code));
        return $otp;
    }
}
