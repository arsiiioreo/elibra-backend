<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Errors\ErrorTranslator;
use App\Models\Department;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    public function all() {
        $departments = Department::all()->where('deleted_at', null)->load('campuses');

        return response()->json($departments);
    }

    public function add(Request $request) {
        $data = Validator::make($request->all(), [
            'code' => 'required',
            'name' => 'required',
            'campus_id' => 'required',
        ]);

        if ($data->fails()) {
            return response()->json(['status' => 'error', 'message' => 'An error occured, please check your inputs and try again.']);
        }

        try {
            $department = new Department();
            $department->code = $request->code;
            $department->name = $request->name;
            $department->campus_id = $request->campus_id;
            $department->save();

            return response()->json(['status' => 'success', 'message' => 'Successfully added '. $department->name . ' department.']);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => ErrorTranslator::translateError($e->getCode())]);
        }
    }

    public function update(Request $request) {
        $data = Validator::make($request->all(), [
            'id' => 'required',
            'code' => 'required',
            'name' => 'required',
        ]);

        if ($data->fails()) {
            return response()->json(['status' => 'error', 'message' => 'An error occured, please check your inputs and try again.']);
        }
        
        $department = Department::find($request->id);
        
        if (!$department) {
            return response()->json(['status' => 'error', 'message' => 'Campus not found.']);
        }
        
        $department->code = $request->code ?? $department->code;
        $department->name = $request->name ?? $department->name;
        $department->campus_id = $request->campus_id ?? $department->campus_id;
        $department->save();
        
        return response()->json(['status' => 'success', 'message' => 'Campus status updated successfully.']);
    }

    public function deleteDepartment(Request $request) {
        $data = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($data->fails()) {
            return response()->json(['status' => 'error', 'message' => 'An error occured, please check your inputs and try again.']);
        }
        
        $department = Department::find($request->id);
        
        if (!$department) {
            return response()->json(['status' => 'error', 'message' => 'Campus not found.']);
        }
        
        $department->delete();
        
        return response()->json(['status' => 'success', 'message' => 'Campus deleted successfully.']);
    }

    public function delete(Request $request) {
        $data = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($data->fails()) {
            return response()->json(['status' => 'error', 'message' => 'An error occured, please check your inputs and try again.']);
        }
        
        $department = Department::find($request->id);
        
        if (!$department) {
            return response()->json(['status' => 'error', 'message' => 'Campus not found.']);
        }
        
        $department->deleted_at = now();
        
        return response()->json(['status' => 'success', 'message' => 'Campus deleted successfully.']);
    }
}
