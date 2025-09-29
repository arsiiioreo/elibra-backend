<?php

use App\Http\Controllers\AccessionController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OTPVerifier;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\LibrariansController;
use App\Http\Controllers\School\CampusController;
use App\Http\Controllers\ProfilePhotosController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\SuperAdminControls;
use App\Http\Controllers\UserController;
use App\Mail\EmailVerification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

// Dictionary: API Routes
// a - admin    p - patron
// c - campus   d - department
// co - course  s - subject
// u - user     r - role
// ur - user role




// My own user data
Route::middleware(['jwt.auth'])->group(function () {
    Route::get('/user', [UserController::class, 'user']);
    Route::put('/me/update', [UserController::class, 'update']);
    Route::delete('/me/delete', [UserController::class, 'destroy']);
    Route::post('/upload-pfp', [ProfilePhotosController::class, 'uploadPhoto']);
});



// Authentication Routes
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::get('auth/refresh', [AuthController::class, 'refresh']);
Route::post('/auth/verify-email', [AuthController::class, 'verifyEmail'])->middleware('jwt.auth');
Route::get('/auth/resend-otp', [OTPVerifier::class, 'resendOTP'])->middleware('jwt.auth');




// Campus, Department and Program Management Routes

// Campus Routes
Route::get('/all-c', [CampusController::class, 'all']);
Route::post('/addCampus', [CampusController::class, 'add'])->middleware('jwt.auth', 'role:0');
Route::post('/updateCampus', [CampusController::class, 'update'])->middleware('jwt.auth', 'role:0,1');
Route::post('/deleteCampus', [CampusController::class, 'delete'])->middleware('jwt.auth', 'role:0');

// Department Routes
Route::get('/all-d', [DepartmentController::class, 'all']);
Route::post('/addDepartment', [DepartmentController::class, 'add']);
Route::post('/updateDepartment', [DepartmentController::class, 'update']);
Route::post('/deleteDepartment', [DepartmentController::class, 'delete']);

// Program Routes
Route::get('/all-p', [ProgramController::class, 'all']);
Route::post('/addProgram', [ProgramController::class, 'add']);
Route::post('/updateProgram', [ProgramController::class, 'update']);
Route::post('/deleteProgram', [ProgramController::class, 'delete']);










// Admin Routes
Route::group(['middleware' => ['jwt.auth', 'role:0']], function () {
});



// Librarian Routes
Route::group(['middleware' => ['jwt.auth', 'role:1']], function () {
    // All Librarians
    Route::get('/all-librarians', [LibrariansController::class, 'all']);
    // Item Routes
    Route::post('/add-item', [ItemsController::class, 'create']);
    Route::put('/update-item/{id}', [ItemsController::class, 'update']);
    Route::post('/delete-item', [ItemsController::class, 'destroy']);
    Route::post('/add-copies-of-item', [AccessionController::class, 'accessioning_old']);
});


// Admin and Librarian Routes
Route::group(['middleware' => ['jwt.auth', 'role:0,1']], function () {
    // User Routes
    Route::get('/all-users', [UserController::class, 'allUser']);

    Route::post('/udpate-user-registration', [SuperAdminControls::class, 'updateRegistration']);
});


// Patron Routes



// All Users Routes
