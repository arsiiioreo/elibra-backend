<?php

namespace App\Http\Controllers;

use App\Models\Newspaper;
use Illuminate\Http\Request;

class NewspaperController extends Controller
{
    public static function create($payload)
    {
        Newspaper::create([
            'item_id' => $payload['item_id'],
            'date' => $payload['date'] ?? null,
            'edition' => $payload['edition'] ?? null,
            'pages' => $payload['pages'] ?? null,
        ]);
    }
}


