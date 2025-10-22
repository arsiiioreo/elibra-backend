<?php

namespace App\Http\Controllers;

use App\Models\Electronic;
use Illuminate\Http\Request;

class ElectronicController extends Controller
{
    public static function create($payload)
    {
        Electronic::create([
            'item_id' => $payload['item_id'],
            'file_size' => $payload['file_size'] ?? null,
            'access_url' => $payload['access_url'] ?? null,
        ]);
    }
}
