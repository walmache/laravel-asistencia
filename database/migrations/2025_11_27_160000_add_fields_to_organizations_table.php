<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración para agregar campos adicionales a la tabla organizations
 * 
 * Campos agregados:
 * - ruc: RUC o identificación fiscal de la organización
 * - business_name: Razón social
 * - address: Dirección física
 * - phone: Teléfono de contacto
 * - email: Correo electrónico de contacto
 * - legal_rep_id: Cédula o pasaporte del representante legal
 * - legal_rep_name: Nombres completos del representante legal
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            // Datos de la organización
            $table->string('ruc', 20)->nullable()->after('name')->comment('RUC o identificación fiscal');
            $table->string('business_name')->nullable()->after('ruc')->comment('Razón social');
            $table->string('address')->nullable()->after('description')->comment('Dirección física');
            $table->string('phone', 20)->nullable()->after('address')->comment('Teléfono de contacto');
            $table->string('email')->nullable()->after('phone')->comment('Correo electrónico');
            
            // Datos del representante legal
            $table->string('legal_rep_id', 20)->nullable()->after('email')->comment('Cédula o pasaporte del representante legal');
            $table->string('legal_rep_name')->nullable()->after('legal_rep_id')->comment('Nombres completos del representante legal');
        });
    }

    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn([
                'ruc',
                'business_name',
                'address',
                'phone',
                'email',
                'legal_rep_id',
                'legal_rep_name',
            ]);
        });
    }
};

