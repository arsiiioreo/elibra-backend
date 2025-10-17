<?php

use App\Mail\EmailVerification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/email', function () {
    return view('mails.email_verification', ['otp' => '123456', 'name' => 'Reign']);
});

