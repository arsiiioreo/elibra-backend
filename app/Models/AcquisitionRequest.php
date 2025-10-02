<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcquisitionRequest extends Model
{
    protected $fillable = [
        'requested_by',
        'title',
        'author',
        'publisher',
        'year',
        'edition',
        'quantity',
        'estimated_unit_price',
        'dealer',
        'dept',
        'dept_head',
        'item_type_id',
        'date_ordered',
        'status',
    ];
}
