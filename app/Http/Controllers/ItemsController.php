<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemRequest;
use App\Models\Accession;
use App\Models\Item;
use Exception;
use Illuminate\Http\Request;

class ItemsController extends Controller
{
    public function create(ItemRequest $request) {
        // Logic to create an item
        $item = Item::create(
            $request->safe()->except('copies, id')
        );

        if ($item) {
            try {
                AccessionController::accessioning($item->type, $item->id, (int) $request->copies);   
                return response()->json(['status' => 'success'], 201);
            } catch (Exception $e) {
                // If there's an error creating accessions, delete the item and return an error
                $item->delete();
                return response()->json(['status' => 'error'], 500);
            } 
        } else {
            return response()->json(['status' => 'error'], 500);
        }
    }

    // Logic to update or modify an item's detials
    public function update(ItemRequest $request, $id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json(['status' => 'error', 'message' => 'Item not found'], 404);
        }

        try {
            $item->update($request->only([
                'title',
                'authors',
                'publisher',
                'date_published',
                'call_number',
                'type',
                'campus_id',
                'description',
            ]));

            return response()->json(['status' => 'success'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }




    public function destroy(Request $request)
    {
        $item = Item::find($request->id);

        if (!$item) {
            return response()->json(['status' => 'error', 'message' => 'Item not found'], 404);
        }

        // Check if there are any accessions linked to this item
        // $accessionCount = Accession::where('item_id', $request->id)->count();

        // if ($accessionCount > 0) {
        //     return response()->json(['status' => 'error', 'message' => 'Cannot delete item with existing accessions'], 400);
        // }

        if ($item->delete()) {
            return response()->json(['status' => 'success'], 200);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to delete item'], 500);
        }

    }
}
