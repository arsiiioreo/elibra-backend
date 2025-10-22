<?php

namespace App\Http\Controllers;

use App\Models\Periodical;
use Illuminate\Http\Request;

class PeriodicalController extends Controller
{
    public static function create($payload)
    {
        Periodical::create([
            'item_id' => $payload['item_id'],
            'volume' => $payload['volume'] ?? null,
            'issue' => $payload['issue'] ?? null,
            'pages' => $payload['pages'] ?? null,
        ]);
    }
}
