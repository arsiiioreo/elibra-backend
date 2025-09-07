<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'code',
        'name',
        'campus_id',
    ];

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function programs()
    {
        return $this->hasMany(Program::class);
    }
}
