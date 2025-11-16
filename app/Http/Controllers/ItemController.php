<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Item;
use App\Models\ItemTypes;
use App\Models\Newspaper;
use App\Services\ItemCatalogService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            'query'          => 'nullable|string|max:255',
            'sort'           => 'nullable|in:title,year_published,created_at',
            'order'          => 'nullable|in:asc,desc',
            'item_type_id'   => 'nullable|integer|exists:item_types,id',
            'item_type_id'   => 'nullable|array',
            'language_id*'    => 'nullable|integer|exists:languages,id',
            'year_from'      => 'nullable|digits:4|integer|min:1000|max:' . date('Y'),
            'campus_id'      => 'nullable|integer|exists:campuses,id',
            'year_to'        => 'nullable|digits:4|integer|min:1000|max:' . (date('Y') + 1),
            'page'           => 'nullable|integer|min:1',
            'entries'        => 'nullable|integer|min:1|max:100',
        ])->validate();

        return array_merge([
            'query'          => null,
            'sort'           => 'created_at',
            'order'          => 'desc',
            'item_type_id'   => '',
            'language_id'    => '',
            'year_from'      => null,
            'year_to'        => null,
            'page'           => 1,
            'entries'        => 25,
        ], $validated);
    }


    public function index(Request $request)
    {
        $validated = $this->validation($request);

        $items = Item::query()
            ->whereNull('deleted_at')
            ->with('publisher', 'itemType', 'language', 'book', 'thesis', 'audio', 'serial', 'periodical', 'electronic', 'vertical', 'newspaper') // Add 
            ->when($validated['query'], function ($q, $search) {
                $terms = explode(' ', $search);
                foreach ($terms as $term) {
                    $q->where(function ($inner) use ($term) {
                        $inner
                            ->where('title', 'like', "%$term%")
                            ->orWhere('isbn_issn', 'like', "%$term%")
                            ->orWhere('call_number', 'like', "%$term%")
                            ->orWhere('edition', 'like', "%$term%");
                    });
                }
            })
            ->when(isset($validated['item_type_id']) && $validated['item_type_id*'] !== '', function ($q) use ($validated) {
                $q->where('item_type_id', $validated['item_type_id']);
            })
            ->when(isset($validated['language_id']) && $validated['language_id'] !== '', function ($q) use ($validated) {
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
            ->orderBy($validated['sort'], $validated['order'])
            ->paginate(
                $validated['entries'],
                [
                    'id',
                    'title',
                    'isbn_issn',
                    'edition',
                    'call_number',
                    'publisher_id',
                    'year_published',
                    'item_type_id',
                    'language_id',
                    'remarks',
                    'created_at',
                ],
                'page',
                $validated['page']
            );

        return response()->json($items);
    }

    //For mobile App
    public function indexMobile(Request $request)
    {
        $validated = $this->validation($request);

        $validated['sort'] = $validated['sort'] ?? 'title';
        $validated['order'] = $validated['order'] ?? 'asc';

        $items = Item::query()
            ->whereNull('deleted_at')
            ->with('publisher', 'itemType', 'language', 'book', 'campus', 'thesis', 'audio', 'serial', 'periodical', 'electronic', 'vertical', 'newspaper') // Add 
            ->when($validated['query'], function ($q, $search) {
                $terms = explode(' ', $search);
                foreach ($terms as $term) {
                    $q->where(function ($inner) use ($term) {
                        $inner
                            ->where('title', 'like', "%$term%")
                            ->orWhere('remarks', 'like', "%$term%")
                            ->orWhere('isbn_issn', 'like', "%$term%")
                            ->orWhere('call_number', 'like', "%$term%")
                            ->orWhere('edition', 'like', "%$term%");
                        });
                    }
                })
                ->when(isset($validated['campus_id']) && $validated['campus_id'] !== '', function ($q) use ($validated) {
                    $q->where('campus_id', $validated['campus_id']);
                })
                ->when(isset($validated['item_type_id']) && !empty($validated['item_type_id']), function ($q) use ($validated) {
                    $q->whereIn('item_type_id', $validated['item_type_id']);
                })
                ->when(isset($validated['language_id']) && $validated['language_id'] !== '', function ($q) use ($validated) {
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
                ->orderBy($validated['sort'], $validated['order'])
                ->paginate(
                    $validated['entries'],
                    [
                        'id',
                        'title',
                        'isbn_issn',
                        'edition',
                        'call_number',
                        'publisher_id',
                        'year_published',
                        'item_type_id',
                        'language_id',
                        'campus_id',
                        'remarks',
                        'created_at',
                    ],
                    'page',
                $validated['page']
            );

            return response()->json($items);
    }


    public function create(Request $request)
    {
        // 1) base validation for Item common fields
        $baseRules = [
            'title' => 'required|string|max:255',
            'publisher_id' => 'nullable|integer|exists:publishers,id',
            'year_published' => 'nullable|digits:4|integer|min:1000|max:' . (date('Y') + 1),
            'isbn_issn' => 'nullable|string|max:20',
            'edition' => 'nullable|string|max:100',
            'call_number' => 'required|string|max:100',
            'item_type_id' => 'required|integer|exists:item_types,id',
            'language_id' => 'nullable|integer|exists:languages,id',
            'campus_id' => 'nullable|integer|exists:campuses,id',
            'remarks' => 'nullable|string',
            'maintext_raw' => 'nullable|json',
        ];

        $validatedBase = $request->validate($baseRules);

        // 2) dynamic subtype validation if service exposes rules()
        $itemType = ItemTypes::find($validatedBase['item_type_id']);
        $fullData = $request->all();

        if ($itemType) {
            $serviceClass = 'App\\Services\\ItemTypes\\' . ucfirst($this->normalizeTypeName($itemType->name)) . 'Service';
            if (class_exists($serviceClass)) {
                $service = app($serviceClass);
                if (method_exists($service, 'rules')) {
                    $subRules = $service->rules();
                    // validate only the subtype rules against incoming data
                    $request->validate($subRules);
                }
            }
        }

        // 3) create item via the service
        $item = $this->catalog->createItem($validatedBase, $fullData);

        return response()->json([
            'message' => 'Item created successfully',
            'data' => $item->load('book', 'thesis', 'audio', 'serial', 'periodical', 'electronic', 'vertical', 'newspaper') // load what's relevant
        ], 201);
    }

    protected function normalizeTypeName(string $name): string
    {
        $parts = preg_split('/[^A-Za-z0-9]+/', $name);
        $parts = array_map(fn($p) => ucfirst(strtolower($p)), array_filter($parts));
        return implode('', $parts);
    }
}
