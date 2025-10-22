<?php

namespace App\Services;

use App\Models\Item;
use App\Models\ItemTypes;
use Illuminate\Support\Facades\DB;
use Exception;

class ItemCatalogService
{
    /**
     * createItem is receiving validated base item data + all request data
     * (we’re letting subtype services pick what they need).
     */
    public function createItem(array $baseItemData, array $fullData): Item
    {
        return DB::transaction(function () use ($baseItemData, $fullData) {
            // 1) create base Item
            $item = Item::create($baseItemData);

            // 2) find item type row (for dynamic resolving)
            $type = ItemTypes::find($item->item_type_id);
            if (!$type) {
                throw new Exception("Invalid item type id: {$item->item_type_id}");
            }

            // 3) resolve handler class name using naming convention
            $className = 'App\\Services\\ItemTypes\\' . $this->normalizeTypeName($type->name) . 'Service';

            if (class_exists($className)) {
                // use Laravel container so dependencies can be injected into service if needed
                $handler = app($className);
                // handler can validate using $handler->rules() if present (we’ll handle that in controller)
                $handler->handle($item, $fullData);
            } else {
                // no handler present: log and continue — base item still created
                logger()->warning("No handler class found for item type: {$type->name} ({$className})");
            }

            return $item;
        });
    }

    protected function normalizeTypeName(string $name): string
    {
        // e.g. "research paper" -> "ResearchPaper"
        $parts = preg_split('/[^A-Za-z0-9]+/', $name);
        $parts = array_map(fn($p) => ucfirst(strtolower($p)), array_filter($parts));
        return implode('', $parts);
    }
}
