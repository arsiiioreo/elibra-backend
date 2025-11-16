<?php

namespace App\Http\Controllers;

use App\Models\PatronTypes;
use Illuminate\Http\Request;

class PatronTypesController extends Controller
{
    public function index() {
        $patronTypes = PatronTypes::all();
        
        return response()->json(["data" => $patronTypes]);
    }
}
