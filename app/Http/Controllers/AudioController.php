<?php

namespace App\Http\Controllers;

use App\Models\Audio;
use Illuminate\Http\Request;

class AudioController extends Controller
{
    public static function create($payload)
    {
        Audio::create([
            'item_id' => $payload['item_id'],
            'format' => $payload['format'] ?? null,
            'duration' => $payload['duration'] ?? null,
            'producer' => $payload['producer'] ?? null,
        ]);
    }
}
