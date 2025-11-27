<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'category_id',
        'title',
        'description',
        'short_description',
        'event_type',
        'start_date',
        'end_date',
        'registration_start',
        'registration_deadline',
        'early_bird_deadline',
        'is_free',
        'price',
        'currency',
        'early_bird_price',
        'group_price',
        'max_group_size',
        'provides_certificate',
        'certificate_type',
        'certificate_hours',
        'min_attendance_percentage',
        'location_type',
        'physical_address',
        'room_number',
        'virtual_platform',
        'virtual_link',
        'virtual_password',
        'capacity',
        'waitlist_enabled',
        'max_waitlist',
        'requires_approval',
        'contact_email',
        'contact_phone',
        'status',
        'is_public',
        'is_open_enrollment',
        'featured',
        'published_at',
        'cancellation_policy',
        'refund_policy',
        'terms_conditions',
        'featured_image',
        'brochure_file',
        'qr_code_path',
        'barcode_code',
        'qr_code',
        'barcode',
        'has_sessions',
        'face_threshold',
        'allow_face_checkin',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'registration_start' => 'datetime',
        'registration_deadline' => 'datetime',
        'early_bird_deadline' => 'datetime',
        'published_at' => 'datetime',
        'is_free' => 'boolean',
        'provides_certificate' => 'boolean',
        'waitlist_enabled' => 'boolean',
        'requires_approval' => 'boolean',
        'is_public' => 'boolean',
        'is_open_enrollment' => 'boolean',
        'featured' => 'boolean',
        'has_sessions' => 'boolean',
        'allow_face_checkin' => 'boolean',
        'price' => 'decimal:2',
        'early_bird_price' => 'decimal:2',
        'group_price' => 'decimal:2',
    ];

    /**
     * Boot del modelo - genera códigos automáticamente al crear
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            if (empty($event->qr_code)) {
                $event->qr_code = Str::uuid()->toString();
            }
            if (empty($event->barcode)) {
                $event->barcode = 'EVT' . str_pad(rand(1, 99999999), 8, '0', STR_PAD_LEFT);
            }
        });
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'event_user')
            ->withPivot(['qr_code', 'barcode', 'code_status', 'code_used_at', 'scan_count', 'assigned_at'])
            ->withTimestamps();
    }

    /**
     * Inscripciones al evento (con acceso a códigos)
     */
    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    /**
     * Busca una inscripción por código QR
     */
    public function findRegistrationByQrCode(string $qrCode): ?EventRegistration
    {
        return $this->registrations()->where('qr_code', $qrCode)->first();
    }

    /**
     * Busca una inscripción por código de barras
     */
    public function findRegistrationByBarcode(string $barcode): ?EventRegistration
    {
        return $this->registrations()->where('barcode', $barcode)->first();
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Sesiones del evento (para eventos de larga duración)
     */
    public function sessions()
    {
        return $this->hasMany(EventSession::class)->orderBy('date')->orderBy('order');
    }

    /**
     * Sesiones que requieren asistencia (excluyendo descansos)
     */
    public function attendanceSessions()
    {
        return $this->sessions()->where('requires_attendance', true)->where('is_break', false);
    }

    /**
     * Genera automáticamente sesiones para un evento de larga duración
     * 
     * @param array $schedule Arreglo con la configuración de sesiones
     * Ejemplo:
     * [
     *   ['name' => 'Mañana', 'start' => '09:00', 'end' => '12:00'],
     *   ['name' => 'Almuerzo', 'start' => '12:00', 'end' => '14:00', 'is_break' => true],
     *   ['name' => 'Tarde', 'start' => '14:00', 'end' => '17:00'],
     * ]
     */
    public function generateSessions(array $schedule, ?array $dates = null): void
    {
        // Si no se especifican fechas, usar el rango del evento
        if (empty($dates)) {
            $dates = [];
            $current = $this->start_date->copy();
            while ($current->lte($this->end_date)) {
                $dates[] = $current->format('Y-m-d');
                $current->addDay();
            }
        }

        $order = 0;
        foreach ($dates as $date) {
            foreach ($schedule as $session) {
                $this->sessions()->create([
                    'name' => $session['name'],
                    'date' => $date,
                    'start_time' => $session['start'],
                    'end_time' => $session['end'],
                    'is_break' => $session['is_break'] ?? false,
                    'requires_attendance' => $session['requires_attendance'] ?? !($session['is_break'] ?? false),
                    'order' => $order++,
                    'description' => $session['description'] ?? null,
                ]);
            }
        }

        $this->update(['has_sessions' => true]);
    }

    /**
     * Calcula el porcentaje de asistencia de un usuario
     */
    public function getUserAttendancePercentage(int $userId): float
    {
        if ($this->has_sessions) {
            $totalSessions = $this->attendanceSessions()->count();
            if ($totalSessions === 0) return 0;
            
            $attendedSessions = $this->attendances()
                ->where('user_id', $userId)
                ->whereNotNull('event_session_id')
                ->whereHas('session', fn($q) => $q->where('requires_attendance', true))
                ->count();
            
            return round(($attendedSessions / $totalSessions) * 100, 2);
        }
        
        // Para eventos sin sesiones, es 100% si asistió o 0% si no
        return $this->attendances()->where('user_id', $userId)->exists() ? 100 : 0;
    }

    /**
     * Verifica si un usuario cumple el mínimo de asistencia para certificado
     */
    public function userMeetsAttendanceRequirement(int $userId): bool
    {
        return $this->getUserAttendancePercentage($userId) >= $this->min_attendance_percentage;
    }

    /**
     * Obtiene la duración total del evento en horas
     */
    public function getTotalHoursAttribute(): float
    {
        if ($this->has_sessions) {
            $minutes = $this->attendanceSessions()->get()->sum('duration_minutes');
            return round($minutes / 60, 2);
        }
        
        return round($this->start_date->diffInMinutes($this->end_date) / 60, 2);
    }

    public function getQrCodeUrlAttribute()
    {
        return $this->qr_code_path ? asset('storage/' . $this->qr_code_path) : null;
    }

    // Alias para compatibilidad con código existente
    public function getStartAtAttribute()
    {
        return $this->start_date;
    }

    public function getEndAtAttribute()
    {
        return $this->end_date;
    }

    public function getNameAttribute()
    {
        return $this->title;
    }
}
