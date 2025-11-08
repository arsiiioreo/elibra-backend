<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['name', 'abbrev', 'campus_id'];

    public function campus() : BelongsTo {
        return $this->belongsTo(Campus::class, 'campus_id');
    }

    public function program()  {
        return $this->hasMany(Program::class);
    }
}
