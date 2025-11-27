<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Organization;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

/**
 * Seeder para crear eventos con sesiones de ejemplo
 * Demuestra cómo manejar eventos de larga duración con descansos
 */
class EventSessionsSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('📅 Creando eventos con sesiones...');

        $org = Organization::first();
        $category = Category::where('slug', 'capacitaciones')->first() ?? Category::first();

        // 1. Evento de 1 día con sesiones (8 horas con almuerzo)
        $event1 = Event::create([
            'organization_id' => $org->id,
            'category_id' => $category->id,
            'title' => 'Taller Intensivo de Laravel - 1 Día',
            'short_description' => 'Taller de 8 horas con descanso para almuerzo.',
            'description' => 'Aprende Laravel desde cero en un día intensivo.',
            'event_type' => 'presencial',
            'start_date' => Carbon::tomorrow()->setTime(9, 0),
            'end_date' => Carbon::tomorrow()->setTime(18, 0),
            'registration_start' => Carbon::now(),
            'registration_deadline' => Carbon::tomorrow()->subHours(2),
            'is_free' => false,
            'price' => 50.00,
            'currency' => 'USD',
            'provides_certificate' => true,
            'certificate_type' => 'Certificado de Asistencia',
            'certificate_hours' => 8,
            'min_attendance_percentage' => 75,
            'location_type' => 'presencial',
            'physical_address' => 'Av. Amazonas N23-45, Quito',
            'room_number' => 'Aula 101',
            'capacity' => 30,
            'contact_email' => 'capacitaciones@example.com',
            'status' => 'publicado',
            'is_public' => true,
            'is_open_enrollment' => true,
            'allow_face_checkin' => true,
        ]);

        // Generar sesiones para evento de 1 día
        $event1->generateSessions([
            ['name' => 'Sesión Mañana', 'start' => '09:00', 'end' => '12:00'],
            ['name' => 'Almuerzo', 'start' => '12:00', 'end' => '13:30', 'is_break' => true],
            ['name' => 'Sesión Tarde', 'start' => '13:30', 'end' => '18:00'],
        ]);

        $this->command->info("   ✓ Evento '{$event1->title}' con " . $event1->sessions()->count() . " sesiones");

        // 2. Evento de 2 días (Congreso)
        $event2 = Event::create([
            'organization_id' => $org->id,
            'category_id' => Category::where('slug', 'conferencias')->first()?->id ?? $category->id,
            'title' => 'Congreso de Tecnología 2025 - 2 Días',
            'short_description' => 'Congreso de 2 días con múltiples sesiones.',
            'description' => 'El congreso tecnológico más importante del año.',
            'event_type' => 'hibrido',
            'start_date' => Carbon::now()->addDays(7)->setTime(8, 0),
            'end_date' => Carbon::now()->addDays(8)->setTime(18, 0),
            'registration_start' => Carbon::now(),
            'registration_deadline' => Carbon::now()->addDays(6),
            'is_free' => false,
            'price' => 150.00,
            'currency' => 'USD',
            'provides_certificate' => true,
            'certificate_type' => 'Certificado de Participación',
            'certificate_hours' => 16,
            'min_attendance_percentage' => 80,
            'location_type' => 'hibrido',
            'physical_address' => 'Centro de Convenciones, Quito',
            'room_number' => 'Salón Principal',
            'virtual_platform' => 'Zoom',
            'virtual_link' => 'https://zoom.us/j/123456789',
            'capacity' => 200,
            'contact_email' => 'congreso@example.com',
            'status' => 'publicado',
            'is_public' => true,
            'is_open_enrollment' => true,
            'allow_face_checkin' => true,
        ]);

        // Generar sesiones para evento de 2 días
        $event2->generateSessions([
            ['name' => 'Registro y Bienvenida', 'start' => '08:00', 'end' => '09:00'],
            ['name' => 'Conferencias Mañana', 'start' => '09:00', 'end' => '12:00'],
            ['name' => 'Coffee Break', 'start' => '10:30', 'end' => '11:00', 'is_break' => true],
            ['name' => 'Almuerzo', 'start' => '12:00', 'end' => '14:00', 'is_break' => true],
            ['name' => 'Talleres Tarde', 'start' => '14:00', 'end' => '17:00'],
            ['name' => 'Networking', 'start' => '17:00', 'end' => '18:00', 'is_break' => true, 'requires_attendance' => false],
        ], [
            Carbon::now()->addDays(7)->format('Y-m-d'),
            Carbon::now()->addDays(8)->format('Y-m-d'),
        ]);

        $this->command->info("   ✓ Evento '{$event2->title}' con " . $event2->sessions()->count() . " sesiones");

        // 3. Evento de medio día (sin sesiones múltiples)
        $event3 = Event::create([
            'organization_id' => $org->id,
            'category_id' => Category::where('slug', 'seminarios')->first()?->id ?? $category->id,
            'title' => 'Seminario Express: Introducción a Vue.js',
            'short_description' => 'Seminario de 3 horas sin interrupciones.',
            'description' => 'Aprende los fundamentos de Vue.js en una sesión intensiva.',
            'event_type' => 'virtual',
            'start_date' => Carbon::now()->addDays(3)->setTime(15, 0),
            'end_date' => Carbon::now()->addDays(3)->setTime(18, 0),
            'registration_start' => Carbon::now(),
            'registration_deadline' => Carbon::now()->addDays(2),
            'is_free' => true,
            'price' => 0,
            'currency' => 'USD',
            'provides_certificate' => true,
            'certificate_type' => 'Certificado de Asistencia',
            'certificate_hours' => 3,
            'min_attendance_percentage' => 100, // Debe asistir completo
            'location_type' => 'virtual',
            'virtual_platform' => 'Google Meet',
            'virtual_link' => 'https://meet.google.com/abc-defg-hij',
            'capacity' => 100,
            'contact_email' => 'seminarios@example.com',
            'status' => 'publicado',
            'is_public' => true,
            'is_open_enrollment' => true,
            'has_sessions' => false, // Sin sesiones, es un bloque único
            'allow_face_checkin' => false,
        ]);

        $this->command->info("   ✓ Evento '{$event3->title}' (sin sesiones múltiples)");

        // Resumen
        $this->command->newLine();
        $this->command->info('📊 RESUMEN DE EVENTOS CON SESIONES:');
        $this->command->table(
            ['Evento', 'Duración', 'Sesiones', 'Min. Asistencia'],
            [
                [$event1->title, '1 día (8h)', $event1->sessions()->count(), $event1->min_attendance_percentage . '%'],
                [$event2->title, '2 días (16h)', $event2->sessions()->count(), $event2->min_attendance_percentage . '%'],
                [$event3->title, '3 horas', 'N/A (bloque único)', $event3->min_attendance_percentage . '%'],
            ]
        );
    }
}


