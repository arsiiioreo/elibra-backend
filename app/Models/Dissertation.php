<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dissertation extends Model
{
    protected $fillable = [
        'item_id',
        'type',
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
        return $this->belongsTo(Program::class, 'program_id');
    }
}
