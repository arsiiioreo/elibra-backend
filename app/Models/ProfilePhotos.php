<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class profile_photos extends Model
{
    use SoftDeletes;

    protected $table = "profile_pictures";

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
