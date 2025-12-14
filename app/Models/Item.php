<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'call_number',
        'year_published',
        'place_of_publication',
        'item_type',

        'description',
        'maintext_raw',

        'branch_id',
        'publisher_id',
        'language_id',
    ];

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = ucwords(strtolower($value));
    }

    public function setPlaceOfPublicationAttribute($value)
    {
        $this->attributes['place_of_publication'] = ucwords(strtolower($value));
    }

    public function accession()
    {
        return $this->hasMany(Accessions::class, 'item_id', 'id');
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'item_authors', 'item_id', 'author_id')->withPivot(['role']);
    }

    public function acquisition()
    {
        return $this->belongsToMany(Acquisition::class, 'acquisition_lines', 'item_id', 'acquisition_id')->withPivot(['quantity']);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class, 'publisher_id', 'id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id', 'id');
    }

    public function book()
    {
        return $this->hasOne(Book::class, 'item_id');
    }

    public function thesis()
    {
        return $this->hasOne(Thesis::class, 'item_id');
    }

    public function dissertation()
    {
        return $this->hasOne(Dissertation::class, 'item_id');
    }

    public function audio()
    {
        return $this->hasOne(Audio::class, 'item_id');
    }

    public function serial()
    {
        return $this->hasOne(Serial::class, 'item_id');
    }

    public function periodical()
    {
        return $this->hasOne(Periodical::class, 'item_id');
    }

    public function electronic()
    {
        return $this->hasOne(Electronic::class, 'item_id');
    }

    public function vertical()
    {
        return $this->hasOne(Vertical::class, 'item_id');
    }

    public function newspaper()
    {
        return $this->hasOne(Newspaper::class, 'item_id');
    }
}
