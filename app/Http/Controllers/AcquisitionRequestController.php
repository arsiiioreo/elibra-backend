<?php

namespace App\Http\Controllers;

use App\Models\AcquisitionRequest;
use Illuminate\Http\Request;

class AcquisitionRequestController extends Controller
{
    public function index(Request $request)
    {
        $acquisitions = AcquisitionRequest::all();

        return response()->json(['data' => $acquisitions]);
    }

    public function createRequest(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'item_type' => 'required|string|in:audio,book,dissertation,electronic,newspaper,periodical,serial,thesis,vertical',
        ]);

        AcquisitionRequest::create([
            'title' => $request->title,
            'author' => $request->author,
            'publisher' => $request->publisher,
            'year' => $request->year,
            'edition' => $request->edition,
            'quantity' => $request->quantity,
            'estimated_unit_price' => $request->estimated_unit_price,
            'supplier' => $request->dealer,
            'dept' => $request->dept,
            'dept_head' => $request->dept_head,
            'item_type' => $request->item_type,
            'date_ordered' => now(),
            'status' => 'request',
            'requested_by' => $request->requested_by ?? auth('api')->id(),
        ]);

        return response()->json(['message' => 'Acquisition Request successfully recorded.'], 201);
    }
}
