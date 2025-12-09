<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'role' => $this->role,
        ];
    }

    protected $guarded = [];

    protected $fillable = [
        'last_name',
        'middle_initial',
        'first_name',
        'sex',
        'contact_number',
        'birthdate',
        'email',
        'password',
        'role',
        'pending_registration_approval',
        'profile_picture',
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

    public function getIsAdminAttribute()
    {
        return $this->role === '0';
    }

    public function getIsLibrarianAttribute()
    {
        return $this->role === '1';
    }

    public function getIsPatronAttribute()
    {
        return $this->role === '2';
    }

    // Relationships

    public function patron()
    {
        return $this->hasOne(Patron::class, 'user_id');
    }

    public function librarian()
    {
        return $this->hasOne(Librarian::class, 'user_id');
    }

    public function section()
    {
        return $this->belongsTo($this->librarian(), 'section_id');
    }

    public function profile_photos()
    {
        return $this->belongsTo(ProfilePhotos::class, 'profile_picture', 'id');
    }
}
