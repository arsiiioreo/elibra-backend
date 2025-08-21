<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Technical extends Model
{
    protected $fillable = [
        'item_id',
        'source',
        'volume',
        'location',
        'isbn_issn',
        'accession_type',
        'receipt_type',
        'mode',
        'unit_price',
        'edition',
        'remarks',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
