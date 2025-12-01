<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('query');

        $authors = Author::where('name', 'like', "%{$search}%")->get();

        if ($authors) {
            return response()->json(['status' => 'success', 'data' => $authors]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Author not found.']);
        }
    }
}
