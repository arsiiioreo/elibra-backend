<?php

namespace App\Services\ItemTypes;

class DissertationService
{
    // optional: rules() for validation later
    public function rules(): array
    {
        return [
            'abstract' => 'nullable|string',
            'advisor' => 'required|string',
            'researchers' => 'required|string',
            'program_id' => 'nullable|exists:programs,id'
        ];
    }

    public function handle($item, array $data)
    {
        // using relationship to auto-fill item_id
        return $item->thesis()->create([
            'abstract' => $data['abstract'] ?? null,
            'advisor' => $data['advisor'] ?? "Unknown",
            'researchers' => isset($data['researchers'])
                ? json_encode(array_map('trim', explode(';', $data['researchers'])))
                : json_encode([]),
            'program_id' => $data['program_id'] ?? null,
        ]);
    }
}
