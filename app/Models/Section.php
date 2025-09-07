<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name',
        'campus_id',
    ];

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }
}
