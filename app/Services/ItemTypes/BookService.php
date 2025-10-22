<?php

namespace App\Services\ItemTypes;

class BookService
{
    // optional: rules() for validation later
    public function rules(): array
    {
        return [
            'pages' => 'nullable|integer|min:1',
        ];
    }

    public function handle($item, array $data)
    {
        // using relationship to auto-fill item_id
        return $item->book()->create([
            'pages' => $data['pages'] ?? null,
        ]);
    }
}
