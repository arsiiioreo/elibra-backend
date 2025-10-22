<?php

namespace App\Services\ItemTypes;

class AudioService
{
    // optional: rules() for validation later
    public function rules(): array
    {
        return [
            'format' => 'nullable|string',
            'duration' => 'nullable|string',
            'producer' => 'nullable|string',
        ];
    }

    public function handle($item, array $data)
    {
        // using relationship to auto-fill item_id
        return $item->audio()->create([
            'format' => $data['format'] ?? null,
            'duration' => $data['duration'] ?? null,
            'producer' => $data['producer'] ?? null,
        ]);
    }
}
