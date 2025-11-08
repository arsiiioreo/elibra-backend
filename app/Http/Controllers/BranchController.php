<?php

namespace App\Http\Controllers;

use App\Http\Requests\BranchRequest;
use App\Models\Branch;
use Exception;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    // Fetch all branches for a campus (excluding soft-deleted ones)
    public function all()
    {
        $branches = Branch::all();
        
        return response()->json(["data" => $branches]);
    }
    
    public function get(Request $request, $id) {
        $branches = Branch::where('campus_id', $id)->get();
        if (!$id) {
            $branches = Branch::where('campus_id', $request->id)->get();
        }
        return response()->json(["data" => $branches]);

    }

    // Create new branch
    public function add(BranchRequest $request)
    {
        $validated = $request->validated();

        $branch = Branch::create([
            'name'          => $validated['name'],
            'campus_id'     => $validated['campus_id'],
            'contact_info'  => $validated['contact_info'],
            'department_id' => $validated['department_id'],
            'opening_hour'  => $validated['opening_hour'],
            'closing_hour'  => $validated['closing_hour'],
        ]);

        return response()->json([
            'message' => 'Branch created successfully.',
            'branch'  => $branch
        ], 201);
    }

    // Update existing branch
    public function update(BranchRequest $request)
{
    $validated = $request->validated();

    try {
        $branch = Branch::find($validated['id']);

        if (!$branch) {
            return response()->json([
                'status' => 'error',
                'message' => 'Branch not found.',
            ], 404);
        }

        $branch->update([
            'name'          => $validated['name'],
            'contact_info'  => $validated['contact_info'],
            'department_id' => $validated['department_id'],
            'opening_hour'  => $validated['opening_hour'],
            'closing_hour'  => $validated['closing_hour'],
        ]);

        return response()->json([
            'message' => 'Branch updated successfully.',
            'branch'  => $branch
        ]);
    } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
        ]);
    }
}


    // Soft delete branch
    public function delete(Request $request)
    {
        $branch = Branch::findOrFail($request->id);
        $branch->delete();

        return response()->json([
            'message' => 'Branch deleted successfully.'
        ]);
    }
}
