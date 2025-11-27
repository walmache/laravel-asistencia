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
        'organization_id',
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

    /**
     * Organización a la que pertenece el usuario
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Verifica si el usuario puede acceder a un evento específico
     * 
     * Reglas de acceso:
     * 1. Admins y coordinadores siempre tienen acceso
     * 2. Si el evento es de inscripción abierta (is_open_enrollment = true)
     * 3. Si el usuario pertenece a la misma organización del evento
     * 4. Si el evento no tiene organización (evento de plataforma)
     */
    public function canAccessEvent(Event $event): bool
    {
        // Admins y coordinadores siempre tienen acceso
        if ($this->hasRole(['admin', 'coordinator'])) {
            return true;
        }

        // Evento de inscripción abierta
        if ($event->is_open_enrollment) {
            return true;
        }

        // Evento sin organización específica (evento de plataforma)
        if (is_null($event->organization_id)) {
            return true;
        }

        // Usuario pertenece a la misma organización del evento
        if ($this->organization_id && $this->organization_id === $event->organization_id) {
            return true;
        }

        return false;
    }

    /**
     * Obtiene los eventos disponibles para este usuario
     * Incluye eventos de inscripción abierta y eventos de su organización
     */
    public function availableEvents()
    {
        if ($this->hasRole(['admin', 'coordinator'])) {
            return Event::query();
        }

        return Event::where(function ($query) {
            $query->where('is_open_enrollment', true)
                  ->orWhereNull('organization_id');
            
            if ($this->organization_id) {
                $query->orWhere('organization_id', $this->organization_id);
            }
        })->where('is_public', true);
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
