<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request) {
        $data = Validator::make($request->all(), [
            'user' => 'required',
            'password' => 'required|min:6',
        ]);

        // if ($data->fails()) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => $data->errors(),
        //     ], 422);
        // }

        $user = User::where('email', $request->user)
            ->first();
        
        if (!$user) {
            $user = Student::where('id_number', $request->user)->first();
            if ($user) {
                $user = User::where('id', $user->user_id)->first();
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found.'], 404);
            }
        }

        if (!$user || !password_verify($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Incorrect Password, please try again.',
            ], 401);
        }

        // Assuming you have a method to generate a token or session
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'status' => 'success',
            'message' => 'Login successful.',
            'token' => $token,
            'user' => $user,
        ], 200);
    }
}
