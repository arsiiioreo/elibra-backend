<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Patron extends Model
{
    protected $fillable = [
        'user_id',
        'id_number',
        'ebc',
        'program_id',
        'campus_id',
        'patron_type_id',
        'external_organization',
        'address'
    ];

    public function user() :BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function patron_type() : BelongsTo {
        return $this->belongsTo(PatronTypes::class);
    }

    public function program() : BelongsTo {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function attendance_log() {
        $this->hasMany(AttendanceLog::class, 'program_id', 'id');
    }
}
