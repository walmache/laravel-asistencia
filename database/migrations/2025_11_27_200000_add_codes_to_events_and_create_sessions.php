<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Migración para:
     * 1. Agregar códigos QR y de barras únicos a eventos
     * 2. Crear tabla de sesiones para eventos de larga duración
     * 3. Modificar asistencias para soportar check-in/check-out por sesión
     */
    public function up(): void
    {
        // 1. Agregar campos de códigos a eventos
        Schema::table('events', function (Blueprint $table) {
            // Código QR único (UUID para mayor seguridad)
            $table->string('qr_code', 64)->unique()->nullable()->after('barcode_code');
            // Código de barras corto (más fácil de escanear)
            $table->string('barcode', 20)->unique()->nullable()->after('qr_code');
            // Indica si el evento tiene múltiples sesiones
            $table->boolean('has_sessions')->default(false)->after('barcode');
        });

        // 2. Crear tabla de sesiones de eventos
        Schema::create('event_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Ej: "Sesión Mañana", "Día 1 - Tarde"
            $table->date('date'); // Fecha de la sesión
            $table->time('start_time'); // Hora de inicio
            $table->time('end_time'); // Hora de fin
            $table->boolean('is_break')->default(false); // Si es un descanso/almuerzo
            $table->boolean('requires_attendance')->default(true); // Si requiere registro
            $table->integer('order')->default(0); // Orden de la sesión
            $table->text('description')->nullable();
            $table->timestamps();
            
            // Índice compuesto para búsquedas eficientes
            $table->index(['event_id', 'date', 'order']);
        });

        // 3. Modificar tabla de asistencias
        Schema::table('attendances', function (Blueprint $table) {
            // Sesión específica (nullable para eventos sin sesiones)
            $table->foreignId('event_session_id')
                  ->nullable()
                  ->after('event_id')
                  ->constrained('event_sessions')
                  ->nullOnDelete();
            
            // Hora de salida (para control completo)
            $table->timestamp('check_out_at')->nullable()->after('check_in_at');
            
            // Tipo de registro (entrada o salida)
            $table->enum('check_type', ['in', 'out', 'both'])->default('in')->after('check_out_at');
            
            // Duración calculada en minutos (se actualiza al hacer check-out)
            $table->integer('duration_minutes')->nullable()->after('check_type');
        });

        // 4. Generar códigos para eventos existentes
        $events = \App\Models\Event::all();
        foreach ($events as $event) {
            $event->update([
                'qr_code' => Str::uuid()->toString(),
                'barcode' => 'EVT' . str_pad($event->id, 8, '0', STR_PAD_LEFT) . Str::random(4),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['event_session_id']);
            $table->dropColumn(['event_session_id', 'check_out_at', 'check_type', 'duration_minutes']);
        });

        Schema::dropIfExists('event_sessions');

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['qr_code', 'barcode', 'has_sessions']);
        });
    }
};


