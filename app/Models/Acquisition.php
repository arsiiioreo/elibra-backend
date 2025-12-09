<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Acquisition extends Model
{
    protected $fillable = [
        'purchaseId',
        'acquisition_mode',
        'dealer',
        'acquisition_date',
        'remarks',

        'received_by',
    ];

    public function item()
    {
        return $this->belongsToMany(Item::class, 'acquisition_lines', 'acquisition_id', 'item_id');
    }

    public function acquisition_lines()
    {
        return $this->belongsTo(AcquisitionLine::class, 'id', 'acquisition_id');
    }
}
