<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SuperAdminControls extends Controller
{
    public function approveRegistration($id)
    {
        $user = User::find($id);
        if (!$user) {   
            return response()->json([
                'status' => 'error',
                'message' => 'User not found.'
            ], 404);
        }

        $user->pending_registration_approval = '0';
        $user->save();
        
        return response()->json([
            'status' => 'success',
            'message' => 'User registration approved successfully.'
        ], 200);
    }
}
