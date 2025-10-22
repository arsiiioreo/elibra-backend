<?php

namespace App\Services\ItemTypes;

class PeriodicalService
{
    // optional: rules() for validation later
    public function rules(): array
    {
        return [
            'volume' => 'nullable|string',
            'issue' => 'nullable|string',
            'pages' => 'nullable|string',
        ];
    }

    public function handle($item, array $data)
    {
        // using relationship to auto-fill item_id
        return $item->periodical()->create([
            'volume' => $data['volume'] ?? null,
            'issue' => $data['issue'] ?? null,
            'pages' => $data['pages'] ?? null,
        ]);
    }
}
