<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vertical extends Model
{
    protected $fillable = [
        'item_id',
        'description',
        'category',
        'keyword',
        'extent',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
