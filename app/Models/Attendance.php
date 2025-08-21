<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'section_id',
        'time_in',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function section() {
        return $this->belongsTo(Section::class, 'section_id');
    }
}
