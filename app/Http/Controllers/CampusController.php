<?php

namespace App\Http\Controllers;

use App\Models\Campus;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CampusController extends Controller
{
    public function all(Request $request) // List all campuses with optional filters
    {
        $validated = $this->validateCampusParams($request);

        $campuses = Campus::query()->whereNull('deleted_at')
            ->when($validated['query'], function ($q, $search) {
                $terms = explode(' ', $search);
                foreach ($terms as $term) {
                    $q->where(function ($inner) use ($term) {
                        $inner->where('name', 'like', "%$term%")
                            ->orWhere('abbrev', 'like', "%$term%")
                            ->orWhere('address', 'like', "%$term%");
                    });
                }
            })
            ->orderBy($validated['sort'], $validated['order'])
            ->paginate(
                $validated['per_page'],
                ['*'],
                'page',
                $validated['page']
            );

        return response()->json($campuses);
    }

    private function validateCampusParams(Request $request): array
    {
        $validated = Validator::make($request->all(), [
            'query'     => 'nullable|string|max:255',
            'sort'      => 'nullable|in:name,abbrev,address,created_at,updated_at',
            'order'     => 'nullable|in:asc,desc',
            'page'      => 'nullable|integer|min:1',
            'per_page'  => 'nullable|integer|min:1|max:100',
        ])->validate();

        return array_merge([
            'query'     => null,
            'sort'      => 'created_at',
            'order'     => 'desc',
            'page'      => 1,
            'per_page'  => 10,
        ], $validated);
    }

    public function add(Request $request)
    {
        $data = Validator::make($request->all(), [
            'name' => 'required',
            'abbrev' => 'required',
            'address' => 'required',
        ]);

        if ($data->fails()) {
            return response()->json(['status' => 'error', 'message' => 'An error occured, please check your inputs and try again.']);
        }

        try {
            $campus = new Campus();
            $campus->name = $request->name;
            $campus->abbrev = $request->abbrev;
            $campus->address = $request->address;
            $campus->save();

            return response()->json(['status' => 'success', 'message' => 'Successfully added ' . $campus->campus . ' campus.']);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
