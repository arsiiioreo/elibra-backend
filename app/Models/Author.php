<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Author extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];

    public function item()
    {
        return $this->belongsToMany(Item::class, 'items_authors', 'author_id', 'item_id');
    }
}
