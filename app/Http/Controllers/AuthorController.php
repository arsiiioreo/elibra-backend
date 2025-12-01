<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    public function create(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        $author = Author::create(['name' => $request->name]);

        if ($author) {
            return response()->json(['status' => 'success', 'message' => 'Author added successfully.', 'data' => $author]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Author added unsuccessfully.']);
        }
    }
}
