<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class profile_photos extends Model
{
    protected $fillable = [
        'user_id', 
        'original_name', 
        'stored_name', 
        'path'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
