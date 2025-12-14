<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemAuthors extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'item_id',
        'author_id',
        'role',
    ];

    public function author()
    {
        return $this->belongsTo(Author::class, 'author_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }
}
