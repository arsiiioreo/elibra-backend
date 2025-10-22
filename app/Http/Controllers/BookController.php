<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public static function create($payload)
    {
        Book::create([
            'item_id' => $payload['item_id'],
            'pages' => $payload['pages'] ?? null,
        ]);
    }
}
