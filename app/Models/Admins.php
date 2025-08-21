<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admins extends Model
{
    protected $fillable = [
        'user_id',
        'section_id',
    ];

    public function circulations() {
        return $this->hasMany(Circulation::class, 'admin_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function section() {
        return $this->belongsTo(Section::class, 'section_id');
    }
}
