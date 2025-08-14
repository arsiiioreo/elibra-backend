<?php

use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Errors\ErrorTranslator;
use App\Http\Controllers\School\CampusController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\ProfilePhotosController;
use App\Http\Controllers\SuperAdminControls;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Campus Routes
Route::get('/all-campus', [CampusController::class, 'allCampus']);
Route::post('/updateCampusStatus', [CampusController::class, 'changeCampusStatus']);
Route::post('/addCampus', [CampusController::class, 'addCampus']);
Route::post('/updateCampus', [CampusController::class, 'updateCampus']);



// User Routes

Route::get('/user', [UserController::class, 'user'])->middleware('auth:sanctum'); // Get my own data

Route::get('/all-users', [UserController::class, 'allUser']);
Route::post('/udpate-user-registration', [SuperAdminControls::class, 'updateRegistration']);
Route::post('/register', [RegistrationController::class, 'registerUser']);

Route::post('/login', [LoginController::class, 'login']);

Route::post('/upload-pfp', [ProfilePhotosController::class, 'uploadPhoto']);