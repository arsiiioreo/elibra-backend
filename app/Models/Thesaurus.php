<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Thesaurus extends Model
{
    protected $fillable = [
        'term',
        'description'
    ];
}
