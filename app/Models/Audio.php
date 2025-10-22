<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Audio extends Model
{
    protected $fillable = [
        'item_id',
        'format',
        'duration',
        'producer',
    ];

    public function item() {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
