<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Errors\ErrorTranslator;
use App\Models\Department;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    public function all()
    {
        $departments = Department::all()->where('deleted_at', null)->load('campuses');

        return response()->json($departments);
    }

    



    public function deleteDepartment(Request $request)
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

        $department->deleted_at = now();

        return response()->json(['status' => 'success', 'message' => 'Campus deleted successfully.']);
    }
}
