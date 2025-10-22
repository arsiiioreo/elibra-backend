<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vertical extends Model
{
    protected $fillable = [
        'item_id',
        'organization',
        'location',
        'notes'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
