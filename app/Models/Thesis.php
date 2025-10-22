<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Thesis extends Model
{
    protected $fillable = [
        'item_id',
        'abstract',
        'advisor',
        'researchers',
        'program_id',
        'doi'
    ];

    public function item() {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function program() {
        return $this->belongsTo(Programs::class, 'program_id');
    }
}
