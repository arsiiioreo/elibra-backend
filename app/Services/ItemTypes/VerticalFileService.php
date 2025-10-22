<?php

namespace App\Services\ItemTypes;

class VerticalFileService
{
    // optional: rules() for validation later
    public function rules(): array
    {
        return [
            'organization' => 'nullable|string',
            'location' => 'nullable|string',
            'notes' => 'nullable|string',
        ];
    }

    public function handle($item, array $data)
    {
        // using relationship to auto-fill item_id
        return $item->vertical()->create([
            'organization' => $data['organization'] ?? null,
            'location' => $data['location'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);
    }
}
