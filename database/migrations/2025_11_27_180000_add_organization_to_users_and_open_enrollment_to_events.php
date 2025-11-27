<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Migración para soportar:
     * 1. Usuarios que pertenecen a una organización (organization_id en users)
     * 2. Eventos de inscripción abierta (is_open_enrollment en events)
     * 
     * Lógica de acceso a eventos:
     * - Si is_open_enrollment = true: Cualquier usuario puede inscribirse
     * - Si is_open_enrollment = false: Solo usuarios de la misma organización del evento
     * - Admins y coordinadores siempre tienen acceso
     */
    public function up(): void
    {
        // Agregar organization_id a usuarios (nullable para usuarios sin organización)
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('organization_id')
                  ->nullable()
                  ->after('role')
                  ->constrained('organizations')
                  ->nullOnDelete();
        });

        // Agregar is_open_enrollment a eventos
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('is_open_enrollment')
                  ->default(false)
                  ->after('is_public')
                  ->comment('true = cualquier usuario puede inscribirse, false = solo usuarios de la organización');
        });

        // Hacer organization_id nullable en events para eventos de plataforma (sin organización específica)
        Schema::table('events', function (Blueprint $table) {
            $table->foreignId('organization_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
            $table->dropColumn('organization_id');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('is_open_enrollment');
        });
    }
};


