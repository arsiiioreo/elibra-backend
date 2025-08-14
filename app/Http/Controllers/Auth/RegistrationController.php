<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegistrationController extends Controller
{
    // User Registration Method 
    public function registerUser(Request $request) {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'sex' => 'required|in:0,1',
            'campus_id' => 'required|exists:campuses,id',
            'role' => 'required|in:1,2',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors(),
            ], 400);
        }

        $user = new User();
        $user->name = $request->name;
        $user->sex = $request->sex;
        $user->campus_id = $request->campus_id;
        $user->role = $request->role;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully.',
            'user' => $user
        ], 201);
    }

}
