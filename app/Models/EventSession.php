<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EventSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'date',
        'start_time',
        'end_time',
        'is_break',
        'requires_attendance',
        'order',
        'description',
    ];

    protected $casts = [
        'date' => 'date',
        'is_break' => 'boolean',
        'requires_attendance' => 'boolean',
    ];

    /**
     * Evento al que pertenece esta sesión
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Asistencias registradas en esta sesión
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Obtiene la fecha y hora de inicio completa
     */
    public function getStartDateTimeAttribute(): Carbon
    {
        return Carbon::parse($this->date->format('Y-m-d') . ' ' . $this->start_time);
    }

    /**
     * Obtiene la fecha y hora de fin completa
     */
    public function getEndDateTimeAttribute(): Carbon
    {
        return Carbon::parse($this->date->format('Y-m-d') . ' ' . $this->end_time);
    }

    /**
     * Duración de la sesión en minutos
     */
    public function getDurationMinutesAttribute(): int
    {
        return $this->start_date_time->diffInMinutes($this->end_date_time);
    }

    /**
     * Verifica si la sesión está activa (en curso)
     */
    public function getIsActiveAttribute(): bool
    {
        $now = Carbon::now();
        return $now->between($this->start_date_time, $this->end_date_time);
    }

    /**
     * Verifica si la sesión ya pasó
     */
    public function getIsPastAttribute(): bool
    {
        return Carbon::now()->isAfter($this->end_date_time);
    }

    /**
     * Verifica si la sesión es futura
     */
    public function getIsFutureAttribute(): bool
    {
        return Carbon::now()->isBefore($this->start_date_time);
    }

    /**
     * Formato legible de la sesión
     */
    public function getFormattedTimeRangeAttribute(): string
    {
        return Carbon::parse($this->start_time)->format('H:i') . ' - ' . Carbon::parse($this->end_time)->format('H:i');
    }
}


