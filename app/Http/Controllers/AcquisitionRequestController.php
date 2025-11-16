<?php

namespace App\Http\Controllers;

use App\Models\AcquisitionRequest;
use Illuminate\Http\Request;

class AcquisitionRequestController extends Controller
{
    public function index(Request $request) {
        $acquisitions = AcquisitionRequest::all();

        return response()->json(['data' => $acquisitions]);
    }

    public function createRequest(Request $request) {
        $data = $request->validate([
            'title' => 'required',
            'item_type_id' => 'required|integer|exists:item_types,id'
        ]);

        AcquisitionRequest::create([
            'requested_by' => $request->requested_by ?? auth('api')->id(),
            'title' => $request->title,
            'author' => $request->author,
            'publisher' => $request->publisher,
            'year' => $request->year,
            'edition' => $request->edition,
            'quantity' => $request->quantity,
            'estimated_unit_price' => $request->estimated_unit_price,
            'dealer' => $request->dealer,
            'dept' => $request->dept,
            'item_type_id' => $request->item_type_id,
            'date_ordered' => now(),
            'status' => 'request',
        ]);

        return response()->json(['message' => 'Acquisition Request successfully recorded.'], 201);
    }
}
