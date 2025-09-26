<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Programs extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'abbrev', 'department_id'];

    public function department() : BelongsTo {
        return $this->belongsTo(Department::class);
    }

}
