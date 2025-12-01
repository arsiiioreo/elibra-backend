<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OpacController extends Controller
{
    private function validation(Request $request): array
    {
        $validated = Validator::make($request->all(), [
            'query' => 'nullable|string|max:255',
            'sort' => 'nullable|in:title,year_published,created_at',
            'order' => 'nullable|in:asc,desc',
            'type' => 'nullable|integer|exists:item_types,id',
            'language_id' => 'nullable|integer|exists:languages,id',
            'year_from' => 'nullable|digits:4|integer|min:1000|max:'.date('Y'),
            'year_to' => 'nullable|digits:4|integer|min:1000|max:'.(date('Y') + 1),
            'page' => 'nullable|integer|min:1',
            'entries' => 'nullable|integer|min:1|max:100',
        ])->validate();

        return array_merge([
            'query' => null,
            'sort' => 'title',
            'order' => 'asc',
            'type' => '',
            'language_id' => '',
            'year_from' => null,
            'year_to' => null,
            'page' => 1,
            'entries' => 25,
        ], $validated);
    }

    public function index(Request $request)
    {
        $validated = $this->validation($request);

        $items = Item::query()
            ->whereNull('deleted_at')
            ->with('publisher', 'itemType', 'language', 'book', 'thesis', 'dissertation','audio', 'serial', 'periodical', 'electronic', 'vertical', 'newspaper', 'accession.branch.campus', 'authors.author') // Add
            ->when($validated['query'], function ($q, $search) {
                $terms = explode(' ', $search);
                foreach ($terms as $term) {
                    $q->where(function ($inner) use ($term) {
                        $inner
                            ->where('title', 'like', "%$term%")
                            ->orWhere('isbn_issn', 'like', "%$term%")
                            ;
                    });
                }
            })
            ->when(!empty($validated['type']), function ($q) use ($validated) {
                $q->where('item_type_id', $validated['type']);
            })
            ->when(!empty($validated['language_id']), function ($q) use ($validated) {
                $q->where('language_id', $validated['language_id']);
            })
            ->when($validated['year_from'] || $validated['year_to'], function ($q) use ($validated) {
                if ($validated['year_from']) {
                    $q->where('year_published', '>=', $validated['year_from']);
                }
                if ($validated['year_to']) {
                    $q->where('year_published', '<=', $validated['year_to']);
                }
            })
            ->orderBy($validated['sort'], $validated['order']);

        return response()->json($items->paginate(
            $validated['entries'],
            ['*'],
            'page',
            $validated['page']
        ));
    }
}
