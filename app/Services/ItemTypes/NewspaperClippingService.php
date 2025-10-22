<?php

namespace App\Services\ItemTypes;

class NewspaperClippingService
{
    public function rules(): array
    {
        return [
            'date' => 'nullable|date',
            'edition' => 'nullable|string|max:255',
            'pages' => 'nullable|integer|min:1',
        ];
    }

    public function handle($item, array $data)
    {
        return $item->newspaper()->create([
            'date' => $data['date'] ?? null,
            'edition' => $data['edition'] ?? null,
            'pages' => $data['pages'] ?? null,
        ]);
    }
}
