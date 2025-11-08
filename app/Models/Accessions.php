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
        'shelf_location',
        'status',
        'branch_id',
        'date_acquired',
        'acquisition_id',
        'price',
        'remarks'
    ];

    public function branch() {
        return $this->belongsTo(Branch::class);
    }
}
