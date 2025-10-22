<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Electronic extends Model
{
    protected $fillable = [
        'item_id',
        'file_size',
        'access_url',
    ];
    
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
