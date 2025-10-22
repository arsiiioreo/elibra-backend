<?php

namespace App\Http\Controllers;

use App\Models\Vertical;
use Illuminate\Http\Request;

class VerticalController extends Controller
{
    public static function create($payload)
    {
        Vertical::create([
            'item_id' => $payload['item_id'],
            'organization' => $payload['organization'] ?? null,
            'location' => $payload['location'] ?? null,
            'notes' => $payload['notes'] ?? null,
        ]);
    }
}
