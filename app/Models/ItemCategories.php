<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemCategories extends Model
{
    protected $fillable = [
        'item_id',
        'category_id'
    ];
}
