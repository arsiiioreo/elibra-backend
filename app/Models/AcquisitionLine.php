<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcquisitionLine extends Model
{
    protected $fillable = [
        'acquisition_id',
        'item_id',
        'accession_id',
        'quantity',
        'unit_price',
        'discount',
        'net_price'
    ];
}
