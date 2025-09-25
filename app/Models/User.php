<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'id'    => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role'  => $this->role,
        ];
    }

    protected $guarded = [];

    protected $fillable = [
        'name', 'email', 'password', 'role', 'sex', 'campus_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];  


    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // User Roles

    public function isAdmin() {
        return $this->role === '0';
    }

    public function isLibrarian() {
        return $this->role === '1';
    }

    public function isPatron() {
        return $this->role === '2';
    }

    // Relationships

    public function patron() {
        return $this->hasOne(Patron::class, 'user_id');
    }

    public function librarian()
    {
        return $this->hasOne(Librarian::class);
    }

    public function campus() {
        return $this->belongsTo(Campus::class, 'campus_id', 'id');
    }

    public function profile_photos() {
        return $this->belongsTo(profile_photos::class, 'profile_picture', 'id');
    }
}
