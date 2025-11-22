<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'face_embedding',
        'face_image_path',
        'consent_face_processing',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'face_embedding' => 'array', // Cast JSON to array for face embedding
        'consent_face_processing' => 'boolean',
    ];

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_user');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'event_user')
                    ->distinct();
    }

    public function hasRole($roles)
    {
        if (is_array($roles)) {
            return in_array($this->role, $roles);
        }
        return $this->role === $roles;
    }
}