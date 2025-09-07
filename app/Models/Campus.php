<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campus extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'abbrev',
        'campus',
        'address',
    ];

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function items() 
    {
        return $this->hasMany(Item::class);
    }
}
