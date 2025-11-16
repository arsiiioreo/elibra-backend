<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Serial extends Model
{
    protected $fillable = [
        'item_id',
        'volume',
        'isbn_issn',
        'issue',
        'pages',
        'doi'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
