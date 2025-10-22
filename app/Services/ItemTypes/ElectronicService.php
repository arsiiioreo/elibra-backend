<?php

namespace App\Services\ItemTypes;

class ElectronicService
{
    // optional: rules() for validation later
    public function rules(): array
    {
        return [
            'file_size' => 'nullable|string',
            'access_url' => 'nullable|string',
        ];
    }

    public function handle($item, array $data)
    {
        // using relationship to auto-fill item_id
        return $item->electronic()->create([
            'file_size' => $data['file_size'] ?? null,
            'access_url' => $data['access_url'] ?? null,
        ]);
    }
}
