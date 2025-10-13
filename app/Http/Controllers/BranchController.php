<?php

namespace App\Http\Controllers;

use App\Http\Requests\BranchRequest;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    // Fetch all branches for a campus (excluding soft-deleted ones)
    public function all($id)
    {
        $branches = Branch::where('campus_id', $id)->get();

        return response()->json($branches);
    }

    // Create new branch
    public function add(BranchRequest $request)
    {
        $branch = Branch::create([
            'name'          => $request->name,
            'campus_id'     => $request->campus_id,
            'contact_info'  => $request->contact_info,
            'department_id' => $request->department_id,
            'opening_hour'  => $request->opening_hour,
            'closing_hour'  => $request->closing_hour,
        ]);

        return response()->json([
            'message' => 'Branch created successfully.',
            'branch'  => $branch
        ], 201);
    }

    // Update existing branch
    public function update(BranchRequest $request)
    {
        $branch = Branch::findOrFail($request->id);

        $branch->update([
            'name'          => $request->name,
            'contact_info'  => $request->contact_info,
            'department_id' => $request->department_id,
            'opening_hour'  => $request->opening_hour,
            'closing_hour'  => $request->closing_hour,
        ]);

        return response()->json([
            'message' => 'Branch updated successfully.',
            'branch'  => $branch
        ]);
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
