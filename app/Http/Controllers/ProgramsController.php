<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProgramsController extends Controller
{
    public function readFromCampus($id)
    {
        $programs = Program::whereHas('department', function ($q) use ($id) {
            $q->where('campus_id', $id);
        })->orderBy('name', 'asc')->get();

        return response()->json(['data' => $programs]);
    }

    public function details($id)
    {
        $user = auth('api')->user();

        if (! $user) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        // Admin view: include soft-deleted + all related data
        if ($user->role === '0') {
            $programs = Program::withTrashed()
                ->with(['department'])
                ->where('department_id', $id)
                ->get();

            if ($programs->isEmpty()) {
                return response()->json(['error' => 'No programs found for this department'], 404);
            }

            return response()->json(['data' => $programs]);
        }

        // Non-admin view: only active programs
        $programs = Program::with(['department'])
            ->where('department_id', $id)
            ->get();

        if ($programs->isEmpty()) {
            return response()->json(['error' => 'No programs found for this department'], 404);
        }

        return response()->json(['data' => $programs]);
    }

    public function add(Request $request)
    {
        $data = Validator::make($request->all(), [
            'name' => 'required',
            'abbrev' => 'required',
            'department_id' => 'required',
        ]);

        if ($data->fails()) {
            return response()->json(['status' => 'error', 'message' => $data->errors()->first()]);
        }

        try {
            $program = Program::create([
                'name' => $request->name,
                'abbrev' => $request->abbrev,
                'department_id' => $request->department_id,
            ]);

            return response()->json(['status' => 'success', 'message' => 'Successfully added '.$program->name.' department.']);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request) {
        $data = Validator::make($request->all(), [
            'id' => 'required',
            'name' => 'required',
            'abbrev' => 'required',
            'department_id' => 'required',
        ]);

        if ($data->fails()) {
            return response()->json(['status' => 'error', 'message' => 'An error occured, please check your inputs and try again.']);
        }
        
        $program = Program::find($request->id);
        
        if (!$program) {
            return response()->json(['status' => 'error', 'message' => 'Campus not found.']);
        }
        
        $program->name = $request->name ?? $program->name;
        $program->abbrev = $request->abbrev ?? $program->abbrev;
        $program->department_id = $request->department_id ?? $program->department_id;
        $program->save();
        
        return response()->json(['status' => 'success', 'message' => 'Campus status updated successfully.']);
    }

    public function delete($id) {
        
        $program = Program::withTrashed()->find($id);
        
        if (!$program) {
            return response()->json(['status' => 'error', 'message' => 'Program not found.']);
        }

        if ($program->deleted_at) {
            $program->forceDelete();
        } else {

            $program->delete();
        }
        
        
        return response()->json(['status' => 'success', 'message' => 'Program deleted successfully.']);
    }
}
