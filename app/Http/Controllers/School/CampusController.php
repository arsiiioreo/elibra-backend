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
    public function all() {
        $campuses = Campus::all()->where('deleted_at', null);

        return response()->json($campuses);
    }

    public function add(Request $request) {
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

            return response()->json(['status' => 'success', 'message' => 'Successfully added '. $campus->campus . ' campus.']);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => ErrorTranslator::translateError($e->getCode())]);
        }
    }

    public function update(Request $request) {
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

    public function delete(Request $request) {
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

    public function deleteCampus(Request $request) {
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
        
        $campus->delete();
        
        return response()->json(['status' => 'success', 'message' => 'Campus deleted successfully.']);
    }
}
