<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'title',
        'authors',
        'publisher',
        'date_published',
        'call_number',
        'type',
        'campus_id',
        'description',
        'condemned_at',
    ];

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }
}
