<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Thesis extends Model
{
    protected $fillable  = [
        'item_id',
        'course',
        'adviser',
        'abstract',
        'extent',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
