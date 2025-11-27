<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Event;
use App\Models\Organization;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ComprehensiveEventSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear Categorías
        $categories = [
            ['name' => 'Conferencias', 'slug' => 'conferencias', 'description' => 'Charlas magistrales y paneles de expertos.'],
            ['name' => 'Talleres', 'slug' => 'talleres', 'description' => 'Sesiones prácticas de aprendizaje.'],
            ['name' => 'Seminarios', 'slug' => 'seminarios', 'description' => 'Reuniones especializadas de naturaleza técnica o académica.'],
            ['name' => 'Networking', 'slug' => 'networking', 'description' => 'Eventos para conectar profesionales.'],
            ['name' => 'Ferias', 'slug' => 'ferias', 'description' => 'Exposiciones comerciales o industriales.'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        $techCorp = Organization::where('name', 'TechCorp Ecuador')->first();
        $conferenciaCat = Category::where('slug', 'conferencias')->first();

        // 2. Crear Evento Completo (Tech Summit 2025)
        if ($techCorp && $conferenciaCat) {
            $startDate = Carbon::now()->addMonths(2)->setTime(9, 0);
            $endDate = $startDate->copy()->addDays(2)->setTime(18, 0);

            Event::create([
                'organization_id' => $techCorp->id,
                'category_id' => $conferenciaCat->id,
                'title' => 'Tech Summit Ecuador 2025',
                'short_description' => 'El evento de tecnología más grande del país, reuniendo a líderes de la industria.',
                'description' => 'Únete a nosotros para dos días de innovación, aprendizaje y networking. Descubre las últimas tendencias en IA, Cloud Computing, Ciberseguridad y más.',
                'event_type' => 'hibrido',
                'start_date' => $startDate,
                'end_date' => $endDate,
                'registration_start' => Carbon::now(),
                'registration_deadline' => $startDate->copy()->subDays(1),
                'early_bird_deadline' => Carbon::now()->addMonth(),
                'is_free' => false,
                'price' => 150.00,
                'currency' => 'USD',
                'early_bird_price' => 100.00,
                'group_price' => 120.00,
                'max_group_size' => 5,
                'provides_certificate' => true,
                'certificate_type' => 'Certificado de Participación',
                'certificate_hours' => 16,
                'min_attendance_percentage' => 80,
                'location_type' => 'hibrido',
                'physical_address' => 'Centro de Convenciones Metropolitano de Quito',
                'room_number' => 'Gran Salón A',
                'virtual_platform' => 'Zoom Events',
                'virtual_link' => 'https://zoom.us/j/123456789',
                'virtual_password' => 'tech2025',
                'capacity' => 500,
                'waitlist_enabled' => true,
                'max_waitlist' => 50,
                'requires_approval' => false,
                'contact_email' => 'eventos@techcorp.ec',
                'contact_phone' => '+593 2 234 5678',
                'status' => 'publicado',
                'is_public' => true,
                'featured' => true,
                'published_at' => Carbon::now(),
                'cancellation_policy' => 'Reembolso completo hasta 30 días antes del evento. 50% hasta 15 días antes.',
                'terms_conditions' => 'Al registrarse acepta los términos de uso y política de privacidad.',
            ]);
        }
        
        $this->command->info('✅ Categorías y Evento completo de prueba creados.');
    }
}


