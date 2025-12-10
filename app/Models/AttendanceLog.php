<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceLog extends Model
{
    protected $fillable = [
        'patron_id',
        'section_id',
        'status',
        'created_at',
    ];

    public function patron()
    {
        return $this->belongsTo(Patron::class, 'patron_id', 'id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branches_id', 'id');
    }
}
