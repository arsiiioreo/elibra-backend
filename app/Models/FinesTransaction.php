<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinesTransaction extends Model
{
    protected $fillable = [
        'patron_id',
        'loan_id',
        'amount',
        'traction_type',
        'processed_by',
        'remarks',
    ];
}
