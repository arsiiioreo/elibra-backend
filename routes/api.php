<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\School\CampusController;
use App\Http\Controllers\ProfilePhotosController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\SuperAdminControls;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Dictionary: API Routes
    // a - admin    p - patron
    // c - campus   d - department
    // co - course  s - subject
    // u - user     r - role
    // ur - user role




// Fetching my own user data
Route::middleware(['jwt.auth'])->group(function () {
    Route::get('/user', [UserController::class, 'user']);
});



// Authentication Routes
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);





// Campus Routes
Route::get('/all-c', [CampusController::class, 'all']);
Route::post('/addCampus', [CampusController::class, 'add']);
Route::post('/updateCampus', [CampusController::class, 'update']);
Route::post('/deleteCampus', [CampusController::class, 'delete']);

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





// User Routes


Route::get('/all-users', [UserController::class, 'allUser']);
Route::post('/udpate-user-registration', [SuperAdminControls::class, 'updateRegistration']);
// Route::post('/register', [RegistrationController::class, 'registerUser']);

// Route::post('/login', [LoginController::class, 'login']);

Route::post('/upload-pfp', [ProfilePhotosController::class, 'uploadPhoto']);