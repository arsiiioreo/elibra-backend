<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Accessions extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'accession_code',
        'shelf_location',
        'status',
        'date_acquired',
        'remarks',

        'item_id',
        'section_id',
        'acquisition_id',
    ];

    public function branch() {
        return $this->belongsTo(Branch::class);
    }
}
