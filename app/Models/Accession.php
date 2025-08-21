<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accession extends Model
{
    protected $fillable = [
        'item_id',
        'accession_code',
        'copy_number',
        'status',
    ];

    public function items()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
