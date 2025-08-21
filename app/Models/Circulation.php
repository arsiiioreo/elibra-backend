<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Circulation extends Model
{
    protected $fillable = [
        'admin_id',
        'item_id',
        'patron_id',
        'status',
        'due_date',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function accession() {
        return $this->belongsTo(Accession::class, 'accession_id');
    }

    public function admin() {
        return $this->belongsTo(Admins::class, 'issued_by');
    }
}
