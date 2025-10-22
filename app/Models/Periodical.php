<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periodical extends Model
{
    protected $fillable = [
        'item_id',
        'volume',
        'issue',
        'pages',
    ];

    public function item() {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
