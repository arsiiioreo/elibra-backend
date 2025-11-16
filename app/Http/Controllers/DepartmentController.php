<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    public function details($id)
    {
        $user = auth('api')->user();

        if ($user && $user->role === '0') {
            $department = Department::withTrashed()
                ->with('campus') // can do this directly instead of load()
                ->where('campus_id', $id)->get();

            if (! $department) {
                return response()->json(['error' => 'Department not found'], 404);
            }

            return response()->json(['data' => $department]);
        } else {
            $department = Department::with('campus') // can do this directly instead of load()
            ->where('id', $id)->get();

            if (! $department) {
                return response()->json(['error' => 'Department not found'], 404);
            }

            return response()->json(['data' => $department]);

        }

        return response()->json(['error' => 'Unauthorized access'], 403);
    }

    public function add(Request $request)
    {
        $data = Validator::make($request->all(), [
            'name' => 'required',
            'abbrev' => 'required',
            'campus_id' => 'required',
        ]);

        if ($data->fails()) {
            return response()->json(['status' => 'error', 'message' => 'An error occured, please check your inputs and try again.']);
        }

        try {
            $department = Department::create([
                'name' => $request->name,
                'abbrev' => $request->abbrev,
                'campus_id' => $request->campus_id,
            ]);

            return response()->json(['status' => 'success', 'message' => 'Successfully added '.$department->name.' department.']);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        $data = Validator::make($request->all(), [
            'id' => 'required',
            'name' => 'required',
            'abbrev' => 'required',
        ]);

        if ($data->fails()) {
            return response()->json(['status' => 'error', 'message' => 'An error occured, please check your inputs and try again.']);
        }

        $department = Department::find($request->id);

        if (! $department) {
            return response()->json(['status' => 'error', 'message' => 'Department not found.']);
        }

        $department->name = $request->name ?? $department->name;
        $department->abbrev = $request->abbrev ?? $department->abbrev;
        $department->campus_id = $request->campus_id ?? $department->campus_id;
        $department->save();

        return response()->json(['status' => 'success', 'message' => 'Department status updated successfully.']);
    }

    public function delete(Request $request)
    {
        $data = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($data->fails()) {
            return response()->json(['status' => 'error', 'message' => 'An error occured, please check your inputs and try again.']);
        }

        $department = Department::find($request->id);

        if (! $department) {
            return response()->json(['status' => 'error', 'message' => 'Campus not found.']);
        }

        $department->delete();

        return response()->json(['status' => 'success', 'message' => 'Campus deleted successfully.']);
    }
}
