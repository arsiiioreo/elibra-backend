<?php

namespace App\Http\Controllers;

use App\Models\Serial;
use Illuminate\Http\Request;

class SerialController extends Controller
{
    public static function create($payload)
    {
        Serial::create([
            'item_id' => $payload['item_id'],
            'volume' => $payload['volume'] ?? null,
            'issue' => $payload['issue'] ?? null,
            'pages' => $payload['pages'] ?? null,
            'doi' => $payload['doi'] ?? null,
        ]);
    }
}
