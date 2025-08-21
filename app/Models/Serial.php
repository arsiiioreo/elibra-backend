<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Serial extends Model
{
    protected $fillable = [
        'item_id',
        'issue_number',
        'frequency',
        'topical_access',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
