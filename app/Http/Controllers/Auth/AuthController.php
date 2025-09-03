<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationRequest;
use App\Models\Patron;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    // Login
    public function login() {
        $user = User::where('email', request('user'))->first() ??
                Patron::where('id_number', request('user'))->first()?->user;
        
        if ($user && auth('api')->attempt(['email' => $user->email, 'password' => request('password')])) {
            $token = auth('api')->login($user);
            return $this->respondWithToken($token);
        } else {
            return response()->json(['status' => 'error', 'message' => "Invalid Credentials."], 401);
        }
    }

    // Register
    public function register(RegistrationRequest $request) {
        $user = User::create($request->validated());

        if ($user) {
            $token = auth('api')->login($user);
            return $this->respondWithToken($token);
        } else {
            return response()->json(['status' => 'error', 'message' => "Invalid Credentials."], 401);
        }
    }

    // Response with Token
    public function respondWithToken($token) {
        return response()->json([
            'status' => 'success',
            'access_token' => $token,
            'token_type' => 'bearer',
        ]);
    }
}
