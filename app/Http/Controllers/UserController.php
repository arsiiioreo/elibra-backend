<?php

namespace App\Http\Controllers;

use App\Models\profile_photos;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class UserController extends Controller
{
    public function allUser() {
        $users = User::with('campus')->get();
        return response()->json($users);
    }

    public function user(Request $request) {
        $user = User::with('campus')
            ->with('profile_photos')
            ->find($request->user()->id);
        
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'sex' => $user->sex,
            'contact_number' => $user->contact_number,
            'email' => $user->email,
            'role' => $user->role,
            'profile_picture' => $user->profile_picture ? asset(('storage/' . $user->profile_photos?->path)) : null,
            'pending_registration_approval' => $user->pending_registration_approval,
            'campus' => [
                'id' => $user?->campus?->id,
                'campus' => $user?->campus?->campus,
                'abbrev' => $user?->campus?->abbrev,
                'address' => $user?->campus?->address,
            ],
        ]);
    }
}
