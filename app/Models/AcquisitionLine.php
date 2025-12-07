<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcquisitionLine extends Model
{
    protected $fillable = [
        'quantity',
        'unit_price',
        'discount',
        'net_price',

        'acquisition_id',
        'item_id',
    ];
}
