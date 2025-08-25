<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Patron;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // ✅ Validate input
        $validator = Validator::make($request->all(), [
            'user' => 'required',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,student'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        // ✅ Find user (email or Patron ID)
        $user = User::where('email', $request->user)->first();

        if (!$user) {
            $student = Patron::where('id_number', $request->user)->first();
            $user = $student ? User::find($student->user_id) : null;
        }

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found.',
            ], 404);
        }

        // ✅ Check pending approval
        if ($user->pending_registration_approval == '1') {
            return response()->json([
                'status' => 'error',
                'message' => 'Your registration is still pending for approval.',
            ], 401);
        }

        // ✅ Try JWT authentication
        $credentials = ['email' => $user->email, 'password' => $request->password];

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Incorrect password, please try again.',
            ], 401);
        }

        // ✅ Attach relations
        $user->load(['campus', 'profile_photos']);

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful.',
            'token' => $token,
            'redirect' => $user->role,
        ], 200);
    }
}
