<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Campus;
use Illuminate\Http\Request;

class CampusController extends Controller
{
    public function allCampus() {
        $campuses = Campus::all();

        return response()->json($campuses);
    }
}
