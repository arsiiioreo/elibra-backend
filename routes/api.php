<?php

use App\Http\Controllers\AcquisitionRequestController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AttendanceLogController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\OTPVerifier;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CampusController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LibrarianController;
use App\Http\Controllers\OpacController;
use App\Http\Controllers\PatronTypesController;
use App\Http\Controllers\ProfilePhotosController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ProgramsController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserLogController;
use App\Models\Language;
use App\Models\Publisher;
use Illuminate\Support\Facades\Route;

// Dictionary: API Routes
// a - admin    p - patron
// c - campus   d - department
// co - course  s - subject
// u - user     r - role
// ur - user role

// All routes marked with ♥ are tested and working
// The routes are fixed and organized by category.

// My own user data
Route::middleware(['jwt.auth'])->group(function () {
    Route::get('/user', [AuthController::class, 'user']); // ♥
    // Route::put('/me/update', [UserController::class, 'update']);
    // Route::delete('/me/delete', [UserController::class, 'destroy']);
    Route::get('/my-activity', [ActivityLogController::class, 'index']); // ♥
    Route::post('/upload-pfp', [ProfilePhotosController::class, 'uploadPhoto']);
});

// Authentication Routes
Route::post('/auth/login', [AuthController::class, 'login']); // ♥
Route::get('/auth/logout', [AuthController::class, 'logout']); // ♥
Route::post('/auth/register', [AuthController::class, 'register']); // ♥
Route::get('auth/refresh', [AuthController::class, 'refresh']); // ♥
Route::get('/auth/verify-email', [AuthController::class, 'verifyEmail']); // Verifying email during registration ♥
// Route::get('/auth/verify-email/{token}/{otp}', [AuthController::class, 'verifyEmail']); // Verifying email during registration ♥
Route::get('/auth/send-otp', [OTPVerifier::class, 'sendOTP'])->middleware('jwt.auth'); // Sending OTP for anything (email verification, 2FA, etc.) ♥
Route::post('/auth/verify-otp', [OTPVerifier::class, 'verifyOTP'])->middleware('jwt.auth'); //

// Campus, Department and Program Management Routes

// Campus Routes
Route::get('/all-c', [CampusController::class, 'all']); // ♥
Route::post('/addCampus', [CampusController::class, 'add'])->middleware('jwt.auth', 'role:0'); // ♥
Route::post('/updateCampus', [CampusController::class, 'update'])->middleware('jwt.auth', 'role:0,1'); // ♥
Route::post('/deleteCampus', [CampusController::class, 'delete'])->middleware('jwt.auth', 'role:0'); // ♥

// Department Routes
Route::group(['prefix' => '/department'], function () {
    // Route::get('/all-d', [DepartmentController::class, 'all']);
    Route::get('/read/{id}', [DepartmentController::class, 'details'])->middleware('jwt.auth', 'role:0,1');  // ♥
    Route::post('/create', [DepartmentController::class, 'add'])->middleware('jwt.auth', 'role:0,1');  // ♥
    Route::post('/update', [DepartmentController::class, 'update']); // ♥
    Route::post('/delete', [DepartmentController::class, 'delete']); // ♥

});

// Branches Routes
Route::group(['prefix' => '/branch', 'middleware' => ['jwt.auth', 'role:0,1']], function () { // ♥
    Route::get('/all', [BranchController::class, 'all']); // ♥
    Route::get('/read/{campus_id}', [BranchController::class, 'get']); // ♥
    Route::post('/create', [BranchController::class, 'add']); // ♥
    Route::post('/update', [BranchController::class, 'update']); // ♥
    Route::delete('/delete', [BranchController::class, 'delete']); // ♥
});

Route::group(['prefix' => '/sections'], function () {
    Route::get('/{branch}', [SectionController::class, 'index']);
});

// Program Routes
Route::group(['prefix' => 'program'], function () {
    Route::get('/read/from-campus/{id}', [ProgramsController::class, 'readFromCampus']); // ♥
    Route::get('/read/{id}', [ProgramsController::class, 'details']); // ♥
    Route::post('/create', [ProgramsController::class, 'add']); // ♥
    Route::put('/update', [ProgramsController::class, 'update']);
    Route::delete('/delete/{id}', [ProgramsController::class, 'delete']);
    Route::post('/updateProgram', [ProgramController::class, 'update']); // ♥
    Route::post('/deleteProgram', [ProgramController::class, 'delete']); // ♥

});

// Patron Types
Route::get('/patron-types', [PatronTypesController::class, 'index']); // ♥

// Item Management
Route::group(['prefix' => '/item'], function () {
    Route::get('/get', [ItemController::class, 'index']); // ♥
    Route::get('/get/{id}', [ItemController::class, 'thisItem']); // ♥
    Route::post('/add', [ItemController::class, 'create']); // ♥

    Route::post('/update/{id}x', [ItemController::class, 'update']); // ♥
});

// Acquisition Requests
Route::group(['prefix' => '/acquisition'], function () {
    Route::post('/request', [AcquisitionRequestController::class, 'createRequest']);
    Route::get('/get', [AcquisitionRequestController::class, 'index']);
});

Route::get('patron/logs', [UserLogController::class, 'index']);

// Attendance Log Management
Route::group(['prefix' => '/attendance'], function () {
    Route::post('/record', [AttendanceLogController::class, 'record']);
    Route::get('/logs', [AttendanceLogController::class, 'logs']);
});

// Languages
Route::group(['prefix' => '/language'], function () {
    Route::get('/', function () {
        return response()->json(Language::all());
    });
});

// Publisher
Route::group(['prefix' => '/publisher'], function () {
    Route::get('/', [PublisherController::class, 'index']);
    Route::post('/create', [PublisherController::class, 'create']);
});

// Author
Route::group(['prefix' => '/author'], function () {
    Route::get('/', [AuthorController::class, 'index']);
    Route::post('/create', [AuthorController::class, 'create']);
});

// Special Routes

Route::group(['prefix' => '/opac'], function () {
    Route::get('/', [OpacController::class, 'index']); // ♥
});

// Admin Routes
Route::group(['prefix' => '/a', 'middleware' => ['jwt.auth', 'role:0']], function () { // Admin Routes with prefix /a
    Route::get('/', [AdminController::class, 'dashboard']);

    Route::get('/users', [UserController::class, 'index']); // Display all users ♥

    Route::group(['prefix' => '/user'], function () { // Admin User Management Routes with prefix /a/user ♥
        Route::get('/details/{id}', [UserController::class, 'details']); // Get details of a specific user ♥
        Route::get('/approve/{id}', [UserController::class, 'approveUser']); // Lets Admin approve a user's registration approval ♥
        Route::get('/reject/{id}', [UserController::class, 'rejectUser']); // Lets Admin approve a user's registration approval ♥
        Route::post('/update/{id}', [UserController::class, 'updateInfo']); // ♥
        Route::post('/create', [UserController::class, 'create']);
        Route::delete('/delete/{id}', [UserController::class, 'destroy']);

        Route::put('/restore/{id}', [UserController::class, 'restore']);

        // Route::put('/update/{id}',[UserController::class, 'update'] );
    });

});

// Librarian Routes
Route::group(['prefix' => '/l', 'middleware' => ['jwt.auth', 'role:1']], function () {
    Route::get('/', [LibrarianController::class, 'dashboard']);
    // All Librarians
    // Route::get('/all-librarians', [LibrariansController::class, 'all']);
    // Item Routes
    // Route::post('/add-item', [ItemsController::class, 'create']);
    // Route::put('/update-item/{id}', [ItemsController::class, 'update']);
    // Route::post('/delete-item', [ItemsController::class, 'destroy']);
    // Route::post('/add-copies-of-item', [AccessionController::class, 'accessioning_old']);
});

// Admin and Librarian Routes
Route::group(['middleware' => ['jwt.auth', 'role:0,1']], function () {
    // User Routes
    // Route::get('/all-users', [UserController::class, 'allUser']);

    // Route::post('/udpate-user-registration', [SuperAdminControls::class, 'updateRegistration']);
});

// Patron Routes

Route::post('/auth/verify-patron', [AuthController::class, 'verifyPatronEmail']);

Route::middleware(['jwt.auth'])->group(function () {
    Route::post('/me/update', [UserController::class, 'update']);

});

// All Users Routes
