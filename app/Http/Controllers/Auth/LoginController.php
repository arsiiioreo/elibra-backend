<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\profile_photos;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;

class LoginController extends Controller
{
    public function login(Request $request) {
        $data = Validator::make($request->all(), [
            'user' => 'required',
            'password' => 'required|min:6',
        ]);

        $user = User::where('email', $request->user)->first();

        if (!$user) {
            $student = Student::where('id_number', $request->user)->first();
            if ($student) {
                $user = User::find($student->user_id);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found.',
                ], 404);
            }
        }

        if (!password_verify($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Incorrect Password, please try again.',
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        $userData = "";

        if ($token) {
            $accessToken = PersonalAccessToken::findToken($token);

            $user = $accessToken?->tokenable;
            if ($user->profile_picture) {
                $path = profile_photos::where('id', $user->profile_picture)->first();
                $user->profile_picture = asset('storage/' . $path->path);
            }

            switch ($user->role) {
                // case '0':
                //     # code...
                //     break;
                case '1': 
                    break;
                case '2':
                    break;
                default:
                    # code...
                    break;

            }

            $userData = json_decode($user);
        }


        return response()->json([
            'status' => 'success',
            'message' => 'Login successful.',
            'token' => $token,
            'user' => $userData, // <-- kasama na agad user
        ], 200);
    }
}
