<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Errors\ErrorTranslator;
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
                        $inner->where('campus', 'like', "%$term%")
                            ->orWhere('abbrev', 'like', "%$term%")
                            ->orWhere('address', 'like', "%$term%");
                    });
                }
            })
            ->when($validated['status'] !== 'all', function ($q) use ($validated) {
                $q->where('is_active', $validated['status']);
            })
            ->orderBy($validated['sort'], $validated['order']);

        return response()->json(
            $campuses->paginate(
                $validated['per_page'],
                ['*'],
                'page',
                $validated['page']
            )
        );
    }

    private function validateCampusParams(Request $request): array
    {
        $validated = Validator::make($request->all(), [
            'query'     => 'nullable|string|max:255',
            'status'    => 'nullable|in:0,1,all',
            'sort'      => 'nullable|in:campus,abbrev,address,created_at,updated_at',
            'order'     => 'nullable|in:asc,desc',
            'page'      => 'nullable|integer|min:1',
            'per_page'  => 'nullable|integer|min:1|max:100',
        ])->validate();

        return array_merge([
            'query'     => null,
            'status'    => '1',
            'sort'      => 'created_at',
            'order'     => 'desc',
            'page'      => 1,
            'per_page'  => 10,
        ], $validated);
    }


    public function add(Request $request)
    {
        $data = Validator::make($request->all(), [
            'abbrev' => 'required',
            'name' => 'required',
            'address' => 'required',
        ]);

        if ($data->fails()) {
            return response()->json(['status' => 'error', 'message' => 'An error occured, please check your inputs and try again.']);
        }

        try {
            $campus = new Campus();
            $campus->abbrev = $request->abbrev;
            $campus->campus = $request->name;
            $campus->address = $request->address;
            $campus->save();

            return response()->json(['status' => 'success', 'message' => 'Successfully added ' . $campus->campus . ' campus.']);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => ErrorTranslator::translateError($e->getCode())]);
        }
    }

    public function update(Request $request)
    {
        $data = Validator::make($request->all(), [
            'id' => 'required',
            'abbrev' => 'required',
            'name' => 'required',
            'address' => 'required',
        ]);

        if ($data->fails()) {
            return response()->json(['status' => 'error', 'message' => 'An error occured, please check your inputs and try again.']);
        }

        $campus = Campus::find($request->id);

        if (!$campus) {
            return response()->json(['status' => 'error', 'message' => 'Campus not found.']);
        }

        $campus->abbrev = $request->abbrev ?? $campus->abbrev;
        $campus->campus = $request->name ?? $campus->name;
        $campus->address = $request->address ?? $campus->address;
        $campus->is_active = $request->status ?? $campus->is_active;
        $campus->save();

        return response()->json(['status' => 'success', 'message' => 'Campus updated successfully.']);
    }

    public function delete(Request $request)
    {
        $data = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($data->fails()) {
            return response()->json(['status' => 'error', 'message' => 'An error occured, please check your inputs and try again.']);
        }

        $campus = Campus::find($request->id);

        if (!$campus) {
            return response()->json(['status' => 'error', 'message' => 'Campus not found.']);
        }

        $campus->deleted_at = now();

        return response()->json(['status' => 'success', 'message' => 'Campus deleted successfully.']);
    }
}
