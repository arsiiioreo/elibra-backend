<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Accessions extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'item_id',
        'accession_number',
        'location_id',
        'status',
        'shelf_location',
        'date_acquired',
        'acquisition',
        'date_acquired',
        'acquisition_id',
        'price',
        'remarks'
    ];
}
