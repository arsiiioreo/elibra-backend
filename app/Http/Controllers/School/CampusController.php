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
    public function allCampus() {
        $campuses = Campus::all();

        return response()->json($campuses);
    }

    public function addCampus(Request $request) {
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

    public function changeCampusStatus(Request $request) {
        $data = Validator::make($request->all(), [
            'id' => 'required',
            'status' => 'required'
        ]);

        if ($data->fails()) {
            return response()->json(['status' => 'error', 'message' => 'An error occured, please check your inputs and try again.']);
        }
        
        $campus = Campus::find($request->id);
        
        if (!$campus) {
            return response()->json(['status' => 'error', 'message' => 'Campus not found.']);
        }
        
        $campus->is_active = $request->status;
        $campus->save();
        
        return response()->json(['status' => 'success', 'message' => 'Campus status updated successfully.']);
    }

    public function updateCampus(Request $request) {
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
        $campus->save();
        
        return response()->json(['status' => 'success', 'message' => 'Campus status updated successfully.']);
    }
}
