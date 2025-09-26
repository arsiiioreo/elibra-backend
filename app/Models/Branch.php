<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'campus_id', 'address', 'contact_info', 'opening_hours', 'closing_hours'];

    public function campus() : BelongsTo {
        return $this->belongsTo(Campus::class);
    }
}
