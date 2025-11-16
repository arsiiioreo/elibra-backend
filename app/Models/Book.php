<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'item_id',
        'pages',
        'isbn_issn',
        'edition',
        'categories_id'
    ];
    
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
