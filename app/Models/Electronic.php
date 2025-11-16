<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Electronic extends Model
{
    protected $fillable = [
        'item_id',
        'file_size',
        'isbn_issn',
        'access_url',
    ];
    
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
