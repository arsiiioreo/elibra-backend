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

    public function acquisition()
    {
        return $this->belongsTo(Acquisition::class, 'acquisition_id', 'id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
