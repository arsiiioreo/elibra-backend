<?php

namespace App\Http\Controllers;

use App\Models\Librarian;
use App\Models\User;
use Illuminate\Http\Request;

class LibrariansController extends Controller
{
    public function all(Request $request)
    {
        $campusId = auth('api')->user()->librarian->campus_id;

        $librarians = Librarian::with('user') // eager load user info
            ->where('campus_id', $campusId)
            ->with('campuses')
            ->with('sections')
            ->get();

        return response()->json([
            'status' => 'success',
            'data'   => $librarians
        ], 200);
    }


    // private function validateCampusParams(Request $request): array
    // {
    //     $validated = Validator::make($request->all(), [
    //         'query'     => 'nullable|string|max:255',
    //         'status'    => 'nullable|in:0,1,all',
    //         'sort'      => 'nullable|in:campus,abbrev,address,created_at,updated_at',
    //         'order'     => 'nullable|in:asc,desc',
    //         'page'      => 'nullable|integer|min:1',
    //         'per_page'  => 'nullable|integer|min:1|max:100',
    //     ])->validate();

    //     return array_merge([
    //         'query'     => null,
    //         'status'    => '1',
    //         'sort'      => 'created_at',
    //         'order'     => 'desc',
    //         'page'      => 1,
    //         'per_page'  => 10,
    //     ], $validated);
    // }
}
