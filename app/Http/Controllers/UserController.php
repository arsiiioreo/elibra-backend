<?php

namespace App\Http\Controllers;

use App\Models\profile_photos;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class UserController extends Controller
{
    public function allUser() {
        $users = User::all();
        return response()->json($users);
    }

    public function user(Request $request) {
        $token = $request->bearerToken();

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

        return response()->json($user);
    }
}
