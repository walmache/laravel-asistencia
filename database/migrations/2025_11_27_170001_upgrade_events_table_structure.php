<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Primero renombramos las columnas existentes
        Schema::table('events', function (Blueprint $table) {
            $table->renameColumn('name', 'title');
            $table->renameColumn('start_at', 'start_date');
            $table->renameColumn('end_at', 'end_date');
        });

        // Luego agregamos las nuevas columnas y modificamos las existentes
        Schema::table('events', function (Blueprint $table) {
            // Información básica
            $table->string('short_description', 500)->nullable()->after('description');
            $table->enum('event_type', ['presencial', 'virtual', 'hibrido'])->after('short_description')->default('presencial');
            $table->foreignId('category_id')->nullable()->after('event_type')->constrained('categories')->nullOnDelete();
            
            // Fechas y Registro
            $table->dateTime('registration_start')->nullable()->after('end_date');
            $table->dateTime('registration_deadline')->nullable()->after('registration_start');
            $table->dateTime('early_bird_deadline')->nullable()->after('registration_deadline');
            
            // Precios
            $table->boolean('is_free')->default(false)->after('early_bird_deadline');
            $table->decimal('price', 8, 2)->nullable()->after('is_free');
            $table->string('currency', 3)->default('USD')->after('price');
            $table->decimal('early_bird_price', 8, 2)->nullable()->after('currency');
            $table->decimal('group_price', 8, 2)->nullable()->after('early_bird_price');
            $table->integer('max_group_size')->nullable()->after('group_price');
            
            // Certificación
            $table->boolean('provides_certificate')->default(false)->after('max_group_size');
            $table->string('certificate_type')->nullable()->after('provides_certificate');
            $table->integer('certificate_hours')->nullable()->after('certificate_type');
            $table->integer('min_attendance_percentage')->default(80)->after('certificate_hours');
            
            // Ubicación
            $table->enum('location_type', ['presencial', 'virtual', 'hibrido'])->after('min_attendance_percentage')->default('presencial');
            $table->text('physical_address')->nullable()->after('location_type');
            $table->string('room_number')->nullable()->after('physical_address');
            $table->string('virtual_platform')->nullable()->after('room_number');
            $table->string('virtual_link')->nullable()->after('virtual_platform');
            $table->string('virtual_password')->nullable()->after('virtual_link');
            
            // Capacidad y Lista de Espera
            $table->integer('capacity')->nullable()->after('virtual_password');
            $table->boolean('waitlist_enabled')->default(false)->after('capacity');
            $table->integer('max_waitlist')->nullable()->after('waitlist_enabled');
            $table->boolean('requires_approval')->default(false)->after('max_waitlist');
            
            // Contacto
            $table->string('contact_email')->nullable()->after('organization_id');
            $table->string('contact_phone', 50)->nullable()->after('contact_email');
            
            // Estado y Visibilidad
            // Nota: 'status' ya existe pero necesitamos cambiar sus valores enum. 
            // En MySQL cambiar enum es complejo, por simplicidad en esta migración lo manejaremos como string validado o alter table nativo si es posible.
            // Laravel no soporta cambiar valores de enum directamente de forma sencilla.
            // Vamos a agregar is_public y featured
            $table->boolean('is_public')->default(true)->after('status');
            $table->boolean('featured')->default(false)->after('is_public');
            $table->dateTime('published_at')->nullable()->after('featured');
            
            // Políticas y Multimedia
            $table->text('cancellation_policy')->nullable()->after('published_at');
            $table->text('refund_policy')->nullable()->after('cancellation_policy');
            $table->text('terms_conditions')->nullable()->after('refund_policy');
            $table->string('featured_image')->nullable()->after('terms_conditions');
            $table->string('brochure_file')->nullable()->after('featured_image');
        });
        
        // Modificar la columna status para aceptar los nuevos valores
        // Esto es específico para MySQL/MariaDB
        DB::statement("ALTER TABLE events MODIFY COLUMN status ENUM('borrador', 'publicado', 'cancelado', 'completado', 'scheduled', 'ongoing', 'finished') DEFAULT 'borrador'");
        
        // Migrar datos antiguos de status a nuevos
        DB::table('events')->where('status', 'scheduled')->update(['status' => 'publicado']);
        DB::table('events')->where('status', 'finished')->update(['status' => 'completado']);
        
        // Limpiar valores antiguos del enum
        DB::statement("ALTER TABLE events MODIFY COLUMN status ENUM('borrador', 'publicado', 'cancelado', 'completado') DEFAULT 'borrador'");
    }

    public function down(): void
    {
        // Revertir cambios es complejo debido a la pérdida de datos potenciales
        // Esta es una migración destructiva en el sentido de estructura
        Schema::table('events', function (Blueprint $table) {
            $table->renameColumn('title', 'name');
            $table->renameColumn('start_date', 'start_at');
            $table->renameColumn('end_date', 'end_at');
            
            $table->dropForeign(['category_id']);
            $table->dropColumn([
                'short_description', 'event_type', 'category_id',
                'registration_start', 'registration_deadline', 'early_bird_deadline',
                'is_free', 'price', 'currency', 'early_bird_price', 'group_price', 'max_group_size',
                'provides_certificate', 'certificate_type', 'certificate_hours', 'min_attendance_percentage',
                'location_type', 'physical_address', 'room_number', 'virtual_platform', 'virtual_link', 'virtual_password',
                'capacity', 'waitlist_enabled', 'max_waitlist', 'requires_approval',
                'contact_email', 'contact_phone',
                'is_public', 'featured', 'published_at',
                'cancellation_policy', 'refund_policy', 'terms_conditions',
                'featured_image', 'brochure_file'
            ]);
        });
    }
};


