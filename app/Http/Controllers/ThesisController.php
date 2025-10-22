<?php

namespace App\Http\Controllers;

use App\Models\Thesis;
use Illuminate\Http\Request;

class ThesisController extends Controller
{
    public static function create($payload)
    {
        Thesis::create([
            'item_id' => $payload['item_id'],
            'abstract' => $payload['abstract'] ?? null,
            'advisor' => $payload['advisor'] ?? null,
            'researchers' => $payload['researchers'] ?? null,
            'program_id' => $payload['program_id'] ?? null,
            'doi' => $payload['doi'] ?? null,
        ]);
    }
}
