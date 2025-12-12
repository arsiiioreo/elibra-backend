<?php

namespace App\Http\Controllers;

use App\Models\Acquisition;
use App\Models\AcquisitionLine;
use App\Models\Item;
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
        try {
            $items = Item::query()
                ->whereNull('deleted_at')
                ->with('publisher', 'language', 'book', 'thesis',
                    'dissertation', 'audio', 'serial', 'periodical', 'electronic',
                    'vertical', 'newspaper', 'accession.section.branch.campus', 'authors') // Add
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
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }

    }

    public function thisItem($id)
    {
        $item = Item::find($id)->load('publisher', 'language', 'book', 'thesis',
            'dissertation', 'audio', 'serial', 'periodical', 'electronic',
            'vertical', 'newspaper', 'accession.section.branch.campus', 'authors', 'acquisition.acquisition_lines'); // Add

        return response()->json(['data' => $item]);
    }

    public function create(Request $request)
    {
        // 1) base validation for Item common fields
        $baseRules = [
            'title' => 'required|string|max:255',
            'call_number' => 'required|string|max:100',
            'year_published' => 'nullable|digits:4|integer|min:1000|max:'.(date('Y') + 1),
            'item_type' => 'required|string|in:audio,book,dissertation,electronic,newspaper,periodical,serial,thesis,vertical',
            'description' => 'nullable|string',
            'maintext_raw' => 'sometimes|json',

            'language_id' => 'nullable|integer|exists:languages,id',
            'publisher_id' => 'nullable|integer|exists:publishers,id',
        ];

        $validatedBase = $request->validate($baseRules);

        try {
            DB::beginTransaction();
            $item = Item::create([
                'title' => $request->title,
                'call_number' => $request->call_number,
                'year_published' => $request->year_published,
                'place_of_publication' => $request->place_of_publication,
                'item_type' => $request->item_type,

                'description' => $request->description ?? null,
                'maintext_raw' => $request->maintext_raw ?? null,

                'branch_id' => auth('api')->user()->librarian->section->branch->id,
                'language_id' => $request->language_id,
                'publisher_id' => $request->publisher_id,
            ]);

            if ($item) {
                ExtendedBibliography::create($request, $item);

                $authors = collect($request->authors)->pluck('id')->toArray();
                $item->authors()->sync($authors);

                $acq = $request->acquisition;
                $lastId = Acquisition::max('id') ?? 0;
                $nextId = $lastId + 1;

                // $purchaseId = 'REF'.now()->format('YmdHis').'-'.str_pad($nextId, 8, '0', STR_PAD_LEFT);
                $purchaseId = 'REF'.now()->format('YmdHis').str_pad($nextId, 8, '0', STR_PAD_LEFT);

                $acquisition = Acquisition::create([
                    'purchaseId' => $purchaseId,
                    'acquisition_mode' => $acq['acquisition_mode'],
                    'acquisition_date' => $acq['acquisition_date'],
                    'dealer' => $acq['dealer'],
                    'remarks' => $acq['acquisition_remarks'] ?? null,

                    'received_by' => auth('api')->user()->librarian->id,
                ]);

                $acquisitionLines = AcquisitionLine::create([
                    'quantity' => $acq['copies'],
                    'unit_price' => $acq['acquisition_mode'] === 'purchased' ? $acq['price'] : 0,
                    'discount' => $acq['discount'] ?? 0,
                    'net_price' => $acq['net_price'] ?? ($acq['copies'] * $acq['price']) - $acq['discount'],
                    'acquisition_id' => $acquisition->id,
                    'item_id' => $item->id,
                ]);

                AccessionsController::create($request, $item, $acquisition);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Item created successfully',
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }

    }

    public function update(Request $request, $id)
    {
        // 1) base validation for Item common fields
        $baseRules = [
            'title' => 'required|string|max:255',
            'call_number' => 'required|string|max:100',
            'year_published' => 'nullable|digits:4|integer|min:1000|max:'.(date('Y') + 1),
            'item_type' => 'required|string|in:audio,book,dissertation,electronic,newspaper,periodical,serial,thesis,vertical',
            'description' => 'nullable|string',
            'maintext_raw' => 'sometimes|json',

            'language_id' => 'nullable|integer|exists:languages,id',
            'publisher_id' => 'nullable|integer|exists:publishers,id',
        ];

        $validatedBase = $request->validate($baseRules);

        try {
            $item = Item::find($id);

            DB::beginTransaction();
            $item->title = $request->title ?? $item->title;
            $item->call_number = $request->call_number ?? $item->call_number;
            $item->year_published = $request->year_published ?? $item->year_published;
            $item->place_of_publication = $request->place_of_publication ?? $item->place_of_publication;
            $item->item_type = $request->item_type ?? $item->item_type;

            $item->description = $request->description ?? $item->description;
            $item->maintext_raw = $request->maintext_raw ?? null;

            $item->branch_id = $request->branch_id ?? $item->branch_id;
            $item->language_id = $request->language_id ?? $item->language_id;
            $item->publisher_id = $request->publisher_id ?? $item->publisher_id;

            if ($item) {
                ExtendedBibliography::update($request, $item);

                // $authors = collect($request->authors)->pluck('id')->toArray();
                // $item->authors()->sync($authors);

                // $acq = $request->acquisition;

                // $acquisition = Acquisition::create([
                //     'acquisition_mode' => $acq['acquisition_mode'],
                //     'acquisition_date' => $acq['acquisition_date'],
                //     'dealer' => $acq['dealer'],
                //     'remarks' => $acq['acquisition_remarks'] ?? null,

                //     'received_by' => auth('api')->user()->librarian->id,
                // ]);

                // $acquisitionLines = AcquisitionLine::create([
                //     'quantity' => $acq['copies'],
                //     'unit_price' => $acq['price'],
                //     'discount' => $acq['discount'] ?? 0,
                //     'net_price' => $acq['net_price'] ?? ($acq['copies'] * $acq['price']) - $acq['discount'],
                //     'acquisition_id' => $acquisition->id,
                //     'item_id' => $item->id,
                // ]);
            }

            $item->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Item updated successfully.',
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }

    }

    public function acquiredNewCopies(Request $request, $id)
    {
        // 1) base validation for Item common fields
        $request->validate([
            'acquisition.acquisition_mode' => 'required|in:gift,donated,exchange,purchased',
            'acquisition.acquisition_date' => 'required|date',
            'acquisition.dealer' => 'required|string|max:255',
            'acquisition.copies' => 'required|integer|min:1|max:199',
            'acquisition.price' => 'nullable|required_if:acquisition.acquisition_mode,purchased|numeric|min:0',
            'acquisition.acquisition_remarks' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();
            $item = Item::findOrFail($id);
            $acq = $request->input('acquisition');

            if ($item) {
                $lastId = Acquisition::max('id') ?? 0;
                $nextId = $lastId + 1;

                // $purchaseId = 'REF'.now()->format('YmdHis').'-'.str_pad($nextId, 8, '0', STR_PAD_LEFT);
                $purchaseId = 'REF'.now()->format('YmdHis').$nextId;

                $acquisition = Acquisition::create([
                    'purchaseId' => $purchaseId,
                    'acquisition_mode' => $acq['acquisition_mode'],
                    'acquisition_date' => $acq['acquisition_date'],
                    'dealer' => $acq['dealer'],
                    'remarks' => $acq['acquisition_remarks'] ?? null,
                    'received_by' => auth('api')->user()->librarian->id,
                ]);

                AcquisitionLine::create([
                    'quantity' => $acq['copies'],
                    'unit_price' => $acq['acquisition_mode'] === 'purchased' ? $acq['price'] : 0,
                    'discount' => 0,
                    'net_price' => $acq['acquisition_mode'] === 'purchased'
                        ? $acq['copies'] * $acq['price']
                        : 0,
                    'acquisition_id' => $acquisition->id,
                    'item_id' => $item->id,
                ]);

                AccessionsController::create($request, $item, $acquisition);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'New acquisitions added successfully',
                'acquisition_id' => $acquisition->id,
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }

    }
}
