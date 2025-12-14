<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Author extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'last_name',
        'first_name',
        'middle_initial',
    ];

    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = ucwords(strtolower($value));
    }

    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = ucwords(strtolower($value));
    }

    public function setMiddleInitialAttribute($value)
    {
        $this->attributes['middle_initial'] = ucwords(strtolower($value));
    }

    public function getFullNameAttribute()
    {
        return $this->last_name.', '.$this->first_name;
    }

    public function item()
    {
        return $this->belongsToMany(Item::class, 'items_authors', 'author_id', 'item_id');
    }
}
