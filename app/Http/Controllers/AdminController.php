<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Campus;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard() {
        $data = [
            'total_campus' => Campus::count('id'),
            'total_user' => User::count('id'),
            'total_librarian' => User::where('role', 1)->count('id'),
            'total_patron' => User::where('role', 2)->count('id'),
        ];

        return response()->json([
            'data' => $data
        ]);
    }
}
