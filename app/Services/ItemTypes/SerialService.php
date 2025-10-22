<?php

namespace App\Services\ItemTypes;

class SerialService
{
    // optional: rules() for validation later
    public function rules(): array
    {
        return [
            'volume' => 'nullable|string',
            'issue' => 'nullable|string',
            'pages' => 'nullable|string',
            'doi' => 'nullable|string',
        ];
    }

    public function handle($item, array $data)
    {
        // using relationship to auto-fill item_id
        return $item->serial()->create([
            'volume' => $data['volume'] ?? null,
            'issue' => $data['issue'] ?? null,
            'pages' => $data['pages'] ?? null,
            'doi' => $data['doi'] ?? null,
        ]);
    }
}
