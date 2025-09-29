<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes;
    
    protected $fillables = ['name', 'abbrev', 'campus_id'];

    public function campus() : BelongsTo {
        return $this->belongsTo(Campus::class);
    }

    public function programs() : HasMany {
        return $this->hasMany(Programs::class);
    }
}
