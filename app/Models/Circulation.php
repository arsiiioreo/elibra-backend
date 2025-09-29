<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Circulation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'accession_id',
        'patron_id',
        'loan_mode_id',
        'borrowed_at',
        'due_at',
        'returned_at',
        'processed_by',
        'status',
        'renewal_count',
        'fine_charged',
        'notes',
    ];

    public function patron() : BelongsTo {
        return $this->belongsTo(Patron::class);
    }

    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }
}
