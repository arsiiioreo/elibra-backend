<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'publisher_id',
        'year_published',
        'isbn_issn',
        'edition',
        'call_number',
        'item_type_id',
        'language_id',
        'remarks',
        'maintext_raw',
    ];

    public function publication() : HasOne {
        return $this->hasOne(Publisher::class);
    }

    public function type() : HasOne {
        return $this->hasOne(ItemTypes::class);
    }
}
