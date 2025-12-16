<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;
use App\Models\StudentProfile;
use App\Models\BookRequest;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',         // student / admin
        'avatar_path',  // profile picture
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relationship: one-to-one student profile
     */
    public function profile()
    {
        return $this->hasOne(StudentProfile::class);
    }

    /**
     * Relationship: user → book requests
     */
    public function requests()
    {
        return $this->hasMany(BookRequest::class);
    }

    /**
     * Accessor for full avatar URL (storage or default)
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar_path) {
            return Storage::url($this->avatar_path);
        }

        // fallback image (put a default avatar here or change the path)
        return asset('images/default-avatar.png');
    }
}
