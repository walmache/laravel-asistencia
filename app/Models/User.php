<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'face_embedding',
        'face_image_path',
        'consent_face_processing',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'face_embedding' => 'array',
            'consent_face_processing' => 'boolean',
        ];
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_user');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function hasRole($roles)
    {
        if (is_string($roles)) {
            $roles = [$roles];
        }
        
        return in_array($this->role, $roles);
    }

    /**
     * Obtiene la URL del avatar del usuario
     * Retorna la imagen facial si existe, null si no
     */
    public function getAvatarUrlAttribute(): ?string
    {
        if ($this->face_image_path) {
            return asset('storage/' . $this->face_image_path);
        }
        return null;
    }
}
