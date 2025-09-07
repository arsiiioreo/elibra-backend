<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Accession extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'item_id',
        'accession_code',
        'copy_number',
        'status',
    ];

    public function items()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
