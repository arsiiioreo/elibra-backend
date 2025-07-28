<?php

use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SuperAdminControls;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/approve-user/id={id}', [SuperAdminControls::class, 'approveRegistration']);
Route::post('/register', [RegistrationController::class, 'registerUser']);
Route::get('/all-users', [UserController::class, 'allUser']);

Route::post('/login', [LoginController::class, 'login']);