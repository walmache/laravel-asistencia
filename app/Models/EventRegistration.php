<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * Modelo para la inscripción de usuarios a eventos (tabla pivote event_user)
 * 
 * Cada inscripción tiene códigos QR y de barras ÚNICOS que:
 * - Solo pueden ser usados una vez
 * - Están vinculados a un usuario específico
 * - Pueden ser revocados si es necesario
 */
class EventRegistration extends Model
{
    protected $table = 'event_user';

    protected $fillable = [
        'event_id',
        'user_id',
        'qr_code',
        'barcode',
        'code_status',
        'code_used_at',
        'scan_count',
        'assigned_at',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'code_used_at' => 'datetime',
    ];

    /**
     * Boot del modelo - genera códigos automáticamente
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($registration) {
            if (empty($registration->qr_code)) {
                $registration->qr_code = Str::uuid()->toString();
            }
            if (empty($registration->barcode)) {
                $registration->barcode = self::generateUniqueBarcode($registration->event_id, $registration->user_id);
            }
            if (empty($registration->assigned_at)) {
                $registration->assigned_at = now();
            }
        });
    }

    /**
     * Evento al que está inscrito
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Usuario inscrito
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Genera un código de barras único
     */
    public static function generateUniqueBarcode(int $eventId, int $userId): string
    {
        $prefix = 'E' . str_pad($eventId, 4, '0', STR_PAD_LEFT);
        $userPart = 'U' . str_pad($userId, 4, '0', STR_PAD_LEFT);
        $random = strtoupper(Str::random(4));
        
        $barcode = $prefix . $userPart . $random;
        
        // Asegurar unicidad
        while (self::where('barcode', $barcode)->exists()) {
            $random = strtoupper(Str::random(4));
            $barcode = $prefix . $userPart . $random;
        }
        
        return $barcode;
    }

    /**
     * Busca una inscripción por código QR
     */
    public static function findByQrCode(string $qrCode): ?self
    {
        return self::where('qr_code', $qrCode)->first();
    }

    /**
     * Busca una inscripción por código de barras
     */
    public static function findByBarcode(string $barcode): ?self
    {
        return self::where('barcode', $barcode)->first();
    }

    /**
     * Verifica si el código está activo y puede ser usado
     */
    public function canUseCode(): bool
    {
        return $this->code_status === 'active';
    }

    /**
     * Verifica si el código ya fue usado
     */
    public function isCodeUsed(): bool
    {
        return $this->code_status === 'used';
    }

    /**
     * Verifica si el código fue revocado
     */
    public function isCodeRevoked(): bool
    {
        return $this->code_status === 'revoked';
    }

    /**
     * Marca el código como usado y registra el escaneo
     * 
     * @return array ['success' => bool, 'message' => string, 'attendance' => ?Attendance]
     */
    public function useCode(string $method = 'qr', ?int $sessionId = null): array
    {
        // Incrementar contador de escaneos
        $this->increment('scan_count');

        // Verificar si el código puede ser usado
        if ($this->code_status === 'revoked') {
            return [
                'success' => false,
                'message' => 'Este código ha sido revocado y no puede ser utilizado.',
                'attendance' => null,
            ];
        }

        if ($this->code_status === 'used') {
            // Verificar si es un evento con sesiones (permite múltiples check-ins)
            $event = $this->event;
            if (!$event->has_sessions) {
                return [
                    'success' => false,
                    'message' => 'Este código ya fue utilizado el ' . $this->code_used_at->format('d/m/Y H:i'),
                    'attendance' => null,
                ];
            }
            // Para eventos con sesiones, verificar si ya tiene asistencia en esta sesión
            if ($sessionId) {
                $existingAttendance = Attendance::where('event_id', $this->event_id)
                    ->where('user_id', $this->user_id)
                    ->where('event_session_id', $sessionId)
                    ->first();
                
                if ($existingAttendance) {
                    return [
                        'success' => false,
                        'message' => 'Ya tiene asistencia registrada en esta sesión.',
                        'attendance' => $existingAttendance,
                    ];
                }
            }
        }

        // Crear registro de asistencia
        $attendance = Attendance::create([
            'event_id' => $this->event_id,
            'event_session_id' => $sessionId,
            'user_id' => $this->user_id,
            'check_in_at' => now(),
            'method' => $method,
            'status' => 'present',
            'metadata' => [
                'registration_id' => $this->id,
                'code_type' => $method === 'barcode' ? 'barcode' : 'qr',
                'code_value' => $method === 'barcode' ? $this->barcode : $this->qr_code,
            ],
        ]);

        // Marcar código como usado (solo si no es evento con sesiones o es primera vez)
        if (!$this->event->has_sessions || $this->code_status === 'active') {
            $this->update([
                'code_status' => 'used',
                'code_used_at' => now(),
            ]);
        }

        return [
            'success' => true,
            'message' => 'Asistencia registrada correctamente.',
            'attendance' => $attendance,
        ];
    }

    /**
     * Revoca el código (lo invalida)
     */
    public function revokeCode(string $reason = null): void
    {
        $this->update([
            'code_status' => 'revoked',
        ]);
    }

    /**
     * Reactiva un código revocado
     */
    public function reactivateCode(): void
    {
        $this->update([
            'code_status' => 'active',
            'code_used_at' => null,
        ]);
    }

    /**
     * Regenera los códigos (útil si se comprometen)
     */
    public function regenerateCodes(): void
    {
        $this->update([
            'qr_code' => Str::uuid()->toString(),
            'barcode' => self::generateUniqueBarcode($this->event_id, $this->user_id),
            'code_status' => 'active',
            'code_used_at' => null,
            'scan_count' => 0,
        ]);
    }

    /**
     * Obtiene el contenido del código QR (JSON con información)
     */
    public function getQrContentAttribute(): string
    {
        return json_encode([
            'type' => 'attendance',
            'code' => $this->qr_code,
            'event_id' => $this->event_id,
            'user_id' => $this->user_id,
            'generated_at' => $this->created_at?->toISOString(),
        ]);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('code_status', 'active');
    }

    public function scopeUsed($query)
    {
        return $query->where('code_status', 'used');
    }

    public function scopeRevoked($query)
    {
        return $query->where('code_status', 'revoked');
    }

    public function scopeForEvent($query, int $eventId)
    {
        return $query->where('event_id', $eventId);
    }
}


