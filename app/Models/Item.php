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
        'campus_id',
        'remarks',
        'maintext_raw',
    ];

    public function publisher() : HasOne {
        return $this->hasOne(Publisher::class, 'id');
    }

    public function itemType() : HasOne {
        return $this->hasOne(ItemTypes::class, 'id', 'item_type_id');
    }

    public function language() : HasOne {
        return $this->hasOne(Language::class, 'id');
    }

    public function book() {
        return $this->hasOne(Book::class, 'item_id');
    }

    public function thesis() {
        return $this->hasOne(Thesis::class, 'item_id');
    }

    public function audio() {
        return $this->hasOne(Audio::class, 'item_id');
    }

    public function serial() {
        return $this->hasOne(Serial::class, 'item_id');
    }

    public function periodical() {
        return $this->hasOne(Periodical::class, 'item_id');
    }

    public function electronic() {
        return $this->hasOne(Electronic::class, 'item_id');
    }

    public function vertical() {
        return $this->hasOne(Vertical::class, 'item_id');
    }

    public function newspaper() {
        return $this->hasOne(Newspaper::class, 'item_id');
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class, 'campus_id');
    }

}
