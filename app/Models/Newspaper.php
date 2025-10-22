<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Newspaper extends Model
{
    protected $fillable = [
        'item_id',
        'date',
        'edition',
        'pages',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
