<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatronTypes extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'can_reserve',
        'reservation_limit',
    ];
}
