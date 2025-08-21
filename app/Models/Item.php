<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'title',
        'author',
        'publisher',
        'date_published',
        'call_number',
        'type',
        'campus_id',
        'desciption',
        'condemned_at',
    ];

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }
}
