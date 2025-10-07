<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemTypeRequest;
use App\Models\ItemTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemTypesController extends Controller
{
    public function create(ItemTypeRequest $request) {
        ItemTypes::create(['name' => $request->name]);

        return response()->json([
            'status' => 'success',
        ]);
    }

    public function read() {
        $data = ItemTypes::withTrashed()->get(['id', 'name', 'deleted_at']);
        return response()->json($data);
    }

    public function update(ItemTypeRequest $r) {
        $item_type = ItemTypes::find($r->id);

        if(!$item_type) {
            return response()->json([
                'status' => 'error'
            ]);
        }

        $item_type->name = $r->name;
        $item_type->save();

        return response()->json(['status'=>'success']);
    }

    public function delete($id) {
        $item_type = ItemTypes::find($id);
        if (is_null($item_type)) {
            $item_type = ItemTypes::withTrashed()->find($id);

            if($item_type) {
                $item_type->forceDelete();
                return response()->json(['status' => 'success']);
            }

            return response()->json(['status' => 'error', 'message' => $id . ' id is not found']);
        }
        $item_type->delete();
        return response()->json(['status' => 'success']);
    }
    
    public function restore($id) {
        $item_type = ItemTypes::withTrashed()->find($id);

        if($item_type) {
            $item_type->restore();
            
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'error']);
        }
    }
}
