<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SuperAdminControls extends Controller
{
    public function updateRegistration(Request $request) {
        $data = Validator::make($request->all(), [
            'action' => 'required',
            'id' => 'required',
        ]);

        if ($data->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid or incomplete arguments, please try again.',
            ], 401);
        }

        $user = User::find($request->id);
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User is not found.',
            ], 404);
        }
        
        if ($request->action) {
            $user->pending_registration_approval = '0';
            $user->save();
        };

        $status = $request->action ? 'approved.' : 'rejected.';
        
        return response()->json([
            'status' => 'success',
            'message' => "User's registration is successfully " . $status,
        ]);
    }
}
