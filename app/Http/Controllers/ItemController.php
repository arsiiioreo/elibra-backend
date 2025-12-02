<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Services\ItemCatalogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    protected $catalog;

    public function __construct(ItemCatalogService $catalog)
    {
        $this->catalog = $catalog;
    }

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
            ->with('publisher', 'language', 'book', 'thesis', 'dissertation', 'audio', 'serial', 'periodical', 'electronic', 'vertical', 'newspaper', 'accession.branch.campus', 'authors.author') // Add
            ->when($validated['query'], function ($q, $search) {
                $terms = explode(' ', $search);
                foreach ($terms as $term) {
                    $q->where(function ($inner) use ($term) {
                        $inner
                            ->where('title', 'like', "%$term%")
                            ->orWhere('isbn_issn', 'like', "%$term%");
                    });
                }
            })
            ->when(! empty($validated['type']), function ($q) use ($validated) {
                $q->where('item_type', $validated['type']);
            })
            ->when(! empty($validated['language_id']), function ($q) use ($validated) {
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

        if ($request->boolean('paginate')) {
            return response()->json($items->paginate(
                $validated['entries'],
                ['*'],
                'page',
                $validated['page']
            ));
        } else {
            return response()->json(['data' => $items->get()]);
        }
    }

    public function thisItem($id)
    {
        $item = Item::find($id)->load(
            'language', 'book', 'thesis',
            'audio', 'serial', 'periodical', 'electronic',
            'vertical', 'newspaper'); // Load item's additional information

        return response()->json($item);
    }

    public function create(Request $request)
    {
        // 1) base validation for Item common fields
        $baseRules = [
            'title' => 'required|string|max:255',
            'call_number' => 'required|string|max:100',
            'year_published' => 'nullable|digits:4|integer|min:1000|max:'.(date('Y') + 1),
            'item_type' => 'required|integer|in:audio,book,dissertation,electronic,newspaper,
                        periodical,serial,vertical',

            'description' => 'nullable|string',
            'maintext_raw' => 'nullable|json',

            'language_id' => 'nullable|integer|exists:languages,id',
            'publisher_id' => 'nullable|integer|exists:publishers,id',
        ];

        $validatedBase = $request->validate($baseRules);

        $item = Item::create([
            'title' => $request->title,
            'call_number' => $request->call_number,
            'year_published' => $request->year_published,
            'item_type' => $request->item_type,

            'description' => $request->description ?? null,
            'maintext_raw' => $request->maintext_raw ?? null,

            'language_id' => $request->language_id,
            'publisher_id' => $request->publisher_id,
        ]);

        if ($item) {
            return response()->json([
                'status' => 'success',
                'message' => 'Item created successfully',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong. Please try again.',
        ]);
    }

    protected function normalizeTypeName(string $name): string
    {
        $parts = preg_split('/[^A-Za-z0-9]+/', $name);
        $parts = array_map(fn ($p) => ucfirst(strtolower($p)), array_filter($parts));

        return implode('', $parts);
    }
}
