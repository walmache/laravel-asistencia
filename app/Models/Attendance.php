<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'event_session_id',
        'user_id',
        'check_in_at',
        'check_out_at',
        'check_type',
        'duration_minutes',
        'method',
        'status',
        'metadata',
    ];

    protected $casts = [
        'check_in_at' => 'datetime',
        'check_out_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Evento al que pertenece esta asistencia
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Sesión específica (si aplica)
     */
    public function session()
    {
        return $this->belongsTo(EventSession::class, 'event_session_id');
    }

    /**
     * Usuario que registró asistencia
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Registra el check-out y calcula la duración
     */
    public function checkOut(string $method = null): self
    {
        $this->check_out_at = Carbon::now();
        $this->check_type = 'both';
        
        if ($this->check_in_at) {
            $this->duration_minutes = $this->check_in_at->diffInMinutes($this->check_out_at);
        }
        
        if ($method) {
            // Agregar método de check-out a metadata
            $metadata = $this->metadata ?? [];
            $metadata['check_out_method'] = $method;
            $this->metadata = $metadata;
        }
        
        $this->save();
        return $this;
    }

    /**
     * Verifica si el usuario ya hizo check-out
     */
    public function hasCheckedOut(): bool
    {
        return !is_null($this->check_out_at);
    }

    /**
     * Obtiene la duración formateada
     */
    public function getFormattedDurationAttribute(): string
    {
        if (!$this->duration_minutes) {
            return '-';
        }
        
        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;
        
        if ($hours > 0) {
            return "{$hours}h {$minutes}m";
        }
        
        return "{$minutes}m";
    }

    /**
     * Scopes para consultas comunes
     */
    public function scopeForEvent($query, $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    public function scopeForSession($query, $sessionId)
    {
        return $query->where('event_session_id', $sessionId);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByMethod($query, $method)
    {
        return $query->where('method', $method);
    }

    public function scopePresent($query)
    {
        return $query->where('status', 'present');
    }

    public function scopeWithCheckOut($query)
    {
        return $query->whereNotNull('check_out_at');
    }

    public function scopeWithoutCheckOut($query)
    {
        return $query->whereNull('check_out_at');
    }

    /**
     * Verifica si la asistencia está completa (check-in y check-out)
     */
    public function getIsCompleteAttribute(): bool
    {
        return !is_null($this->check_in_at) && !is_null($this->check_out_at);
    }
}
