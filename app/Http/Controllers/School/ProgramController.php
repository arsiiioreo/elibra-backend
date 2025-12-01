<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Errors\ErrorTranslator;
use App\Models\Program;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProgramController extends Controller
{
    public function all() {
        $campuses = Program::all()->where('deleted_at', null)->load('departments')->load('campuses');

        return response()->json($campuses);
    }

    public function add(Request $request) {
        $data = Validator::make($request->all(), [
            'code' => 'required',
            'name' => 'required',
            'department_id' => 'required',
        ]);

        if ($data->fails()) {
            return response()->json(['status' => 'error', 'message' => 'An error occured, please check your inputs and try again.']);
        }

        try {
            $program = new Program();
            $program->code = $request->code;
            $program->name = $request->name;
            $program->department_id = $request->department_id;
            $program->save();

            return response()->json(['status' => 'success', 'message' => 'Successfully added '. $program->name . ' program.']);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => ErrorTranslator::translateError($e->getCode())]);
        }
    }

    

    
}
