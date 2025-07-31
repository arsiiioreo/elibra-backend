<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campus extends Model
{
    protected $fillable = [
        'campus',
        'abbrev',
        'address',
        'is-active'
    ];
}
