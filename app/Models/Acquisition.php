<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Acquisition extends Model
{
    protected $fillable = [
        'purchase_order',
        'dealer',
        'acquisition_mode',
        'acquisition_date',
        'total_cost',
        'remarks'
    ];
}
