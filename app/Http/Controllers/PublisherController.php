<?php

namespace App\Http\Controllers;

use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PublisherController extends Controller
{
    public function index(Request $request)
    {
        $publishers = Publisher::query()
            ->whereNull('deleted_at')
            ->when($request->has('query'), function ($q) use ($request) {
                $search = $request->query('query');
                $terms = array_filter(explode(' ', $search));

                foreach ($terms as $term) {
                    $q->where(function ($inner) use ($term) {
                        $inner->where('name', 'like', "%{$term}%")
                            ->orWhere('address', 'like', "%{$term}%");
                    });
                }
            })
            ->get();

        return response()->json(['data' => $publishers]);
    }

    public function create(Request $request) {
        $validated = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255'
        ]);

        $data = $validated->validate();

        $publisher = Publisher::create($data);

        if ($publisher) {
            return response()->json(['status' => 'success', 'data' => $data]);
        }
    }
}
