<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatronTypeLoanPolicy extends Model
{
    protected $fillable= [
        'patron_type_id',
        'loan_mode_id',
        'loan_period_days',
        'max_items',
        'max_renewals',
        'fine_per_due',
        'grace_period',
        'notes',
    ];
}
