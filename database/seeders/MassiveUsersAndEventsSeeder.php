<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Event;
use App\Models\Organization;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Seeder para crear 200 usuarios y asignarlos a eventos
 * 
 * Distribución:
 * - 60% usuarios con organización (acceso a eventos privados de su org)
 * - 40% usuarios sin organización (solo eventos públicos/abiertos)
 * 
 * Los usuarios se asignan a eventos según las reglas de acceso:
 * - Eventos con is_open_enrollment = true: cualquier usuario
 * - Eventos privados: solo usuarios de la misma organización
 */
class MassiveUsersAndEventsSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚀 Iniciando creación masiva de datos...');
        
        // 1. Asegurar que existan organizaciones y categorías
        $this->ensureBaseData();
        
        // 2. Crear eventos variados (públicos y privados)
        $this->createEvents();
        
        // 3. Crear 200 usuarios
        $this->createUsers();
        
        // 4. Asignar usuarios a eventos
        $this->assignUsersToEvents();
        
        $this->command->info('✅ Seeder completado exitosamente.');
    }

    private function ensureBaseData(): void
    {
        $this->command->info('📦 Verificando datos base...');
        
        // Categorías
        if (Category::count() == 0) {
            $categories = [
                ['name' => 'Conferencias', 'slug' => 'conferencias', 'description' => 'Charlas magistrales y paneles de expertos.'],
                ['name' => 'Talleres', 'slug' => 'talleres', 'description' => 'Sesiones prácticas de aprendizaje.'],
                ['name' => 'Seminarios', 'slug' => 'seminarios', 'description' => 'Reuniones especializadas de naturaleza técnica o académica.'],
                ['name' => 'Networking', 'slug' => 'networking', 'description' => 'Eventos para conectar profesionales.'],
                ['name' => 'Capacitaciones', 'slug' => 'capacitaciones', 'description' => 'Cursos y entrenamientos profesionales.'],
            ];
            foreach ($categories as $cat) {
                Category::firstOrCreate(['slug' => $cat['slug']], $cat);
            }
            $this->command->info('   ✓ Categorías creadas');
        }

        // Organizaciones
        if (Organization::count() < 5) {
            $organizations = [
                [
                    'name' => 'Universidad Central del Ecuador',
                    'description' => 'Institución de educación superior pública.',
                    'ruc' => '1760001234001',
                    'business_name' => 'Universidad Central del Ecuador',
                    'address' => 'Av. América y Av. Universitaria, Quito',
                    'phone' => '+593 2 252 6080',
                    'email' => 'info@uce.edu.ec',
                    'legal_rep_id' => '1712345678',
                    'legal_rep_name' => 'Dr. Fernando Sempertegui',
                ],
                [
                    'name' => 'Cámara de Comercio de Quito',
                    'description' => 'Organización empresarial de Quito.',
                    'ruc' => '1790012345001',
                    'business_name' => 'Cámara de Comercio de Quito',
                    'address' => 'Av. Amazonas y República, Quito',
                    'phone' => '+593 2 244 3787',
                    'email' => 'info@ccq.ec',
                    'legal_rep_id' => '1798765432',
                    'legal_rep_name' => 'Patricio Alarcón Espinosa',
                ],
                [
                    'name' => 'TechCorp Ecuador',
                    'description' => 'Empresa líder en soluciones tecnológicas.',
                    'ruc' => '1791234567001',
                    'business_name' => 'TechCorp Ecuador S.A.',
                    'address' => 'Av. 12 de Octubre y Lincoln, Quito',
                    'phone' => '+593 2 234 5678',
                    'email' => 'contacto@techcorp.ec',
                    'legal_rep_id' => '1723456789',
                    'legal_rep_name' => 'Carlos Alberto Mendoza',
                ],
                [
                    'name' => 'Fundación Educativa Horizonte',
                    'description' => 'ONG dedicada a la educación.',
                    'ruc' => '1792345678001',
                    'business_name' => 'Fundación Educativa Horizonte',
                    'address' => 'Calle Guayaquil N5-23, Quito',
                    'phone' => '+593 2 295 1234',
                    'email' => 'info@horizonteedu.org',
                    'legal_rep_id' => '1734567890',
                    'legal_rep_name' => 'María Fernanda López',
                ],
                [
                    'name' => 'Hospital Metropolitano',
                    'description' => 'Centro médico de especialidades.',
                    'ruc' => '1793456789001',
                    'business_name' => 'Hospital Metropolitano S.A.',
                    'address' => 'Av. Mariana de Jesús, Quito',
                    'phone' => '+593 2 399 8000',
                    'email' => 'info@hospitalmetropolitano.org',
                    'legal_rep_id' => '1745678901',
                    'legal_rep_name' => 'Dr. Roberto Espinoza',
                ],
            ];
            
            foreach ($organizations as $org) {
                Organization::firstOrCreate(['ruc' => $org['ruc']], $org);
            }
            $this->command->info('   ✓ Organizaciones creadas/verificadas');
        }
    }

    private function createEvents(): void
    {
        $this->command->info('📅 Creando eventos variados...');
        
        $organizations = Organization::all();
        $categories = Category::all();
        
        // Eliminar eventos existentes para tener datos frescos
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('event_user')->truncate();
        DB::table('attendances')->truncate();
        Event::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $events = [];
        
        // 1. Eventos PÚBLICOS de inscripción abierta (sin organización específica)
        $publicEvents = [
            [
                'title' => 'Webinar Gratuito: Introducción a la IA',
                'short_description' => 'Aprende los fundamentos de la Inteligencia Artificial.',
                'description' => 'Un webinar gratuito y abierto para todos los interesados en conocer los conceptos básicos de la IA.',
                'event_type' => 'virtual',
                'is_free' => true,
                'is_open_enrollment' => true,
                'is_public' => true,
                'organization_id' => null,
                'capacity' => 500,
            ],
            [
                'title' => 'Feria de Empleo Ecuador 2025',
                'short_description' => 'Conecta con las mejores empresas del país.',
                'description' => 'Evento masivo de empleabilidad con más de 100 empresas participantes.',
                'event_type' => 'presencial',
                'is_free' => true,
                'is_open_enrollment' => true,
                'is_public' => true,
                'organization_id' => null,
                'capacity' => 2000,
            ],
            [
                'title' => 'Conferencia Nacional de Emprendimiento',
                'short_description' => 'Inspírate con historias de éxito.',
                'description' => 'Conferencia híbrida con ponentes nacionales e internacionales.',
                'event_type' => 'hibrido',
                'is_free' => false,
                'price' => 25.00,
                'is_open_enrollment' => true,
                'is_public' => true,
                'organization_id' => null,
                'capacity' => 300,
            ],
        ];

        foreach ($publicEvents as $eventData) {
            $events[] = $this->createEventRecord($eventData, $categories->random());
        }
        
        // 2. Eventos de ORGANIZACIONES (algunos abiertos, otros privados)
        foreach ($organizations as $org) {
            // Evento privado de la organización
            $events[] = $this->createEventRecord([
                'title' => "Capacitación Interna - {$org->name}",
                'short_description' => "Capacitación exclusiva para miembros de {$org->name}.",
                'description' => "Evento de formación interna para empleados y colaboradores de la organización.",
                'event_type' => 'presencial',
                'is_free' => true,
                'is_open_enrollment' => false, // PRIVADO
                'is_public' => false,
                'organization_id' => $org->id,
                'capacity' => 50,
            ], $categories->where('slug', 'capacitaciones')->first());
            
            // Evento público de la organización (abierto a externos)
            $events[] = $this->createEventRecord([
                'title' => "Seminario Abierto: {$org->name}",
                'short_description' => "Seminario organizado por {$org->name}, abierto al público.",
                'description' => "Evento público organizado por {$org->name} para compartir conocimientos con la comunidad.",
                'event_type' => 'hibrido',
                'is_free' => false,
                'price' => fake()->randomFloat(2, 15, 100),
                'is_open_enrollment' => true, // ABIERTO
                'is_public' => true,
                'organization_id' => $org->id,
                'capacity' => 150,
            ], $categories->random());
            
            // Taller privado
            $events[] = $this->createEventRecord([
                'title' => "Taller Especializado - {$org->name}",
                'short_description' => "Taller práctico para miembros de {$org->name}.",
                'description' => "Sesión práctica exclusiva para personal de la organización.",
                'event_type' => 'presencial',
                'is_free' => true,
                'is_open_enrollment' => false, // PRIVADO
                'is_public' => false,
                'organization_id' => $org->id,
                'capacity' => 30,
            ], $categories->where('slug', 'talleres')->first());
        }
        
        // 3. Más eventos públicos variados
        for ($i = 1; $i <= 5; $i++) {
            $events[] = $this->createEventRecord([
                'title' => "Tech Meetup Ecuador #{$i}",
                'short_description' => "Encuentro mensual de la comunidad tech.",
                'description' => "Networking y charlas sobre tecnología.",
                'event_type' => 'presencial',
                'is_free' => true,
                'is_open_enrollment' => true,
                'is_public' => true,
                'organization_id' => $organizations->random()->id,
                'capacity' => 80,
            ], $categories->where('slug', 'networking')->first());
        }
        
        $this->command->info("   ✓ " . count($events) . " eventos creados");
    }

    private function createEventRecord(array $data, ?Category $category): Event
    {
        $startDate = Carbon::now()->addDays(rand(7, 90))->setTime(rand(8, 14), 0);
        $endDate = $startDate->copy()->addHours(rand(2, 8));
        
        return Event::create(array_merge([
            'category_id' => $category?->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'registration_start' => Carbon::now(),
            'registration_deadline' => $startDate->copy()->subDays(1),
            'currency' => 'USD',
            'price' => $data['is_free'] ? 0 : ($data['price'] ?? 0),
            'provides_certificate' => fake()->boolean(60),
            'certificate_type' => 'Certificado de Asistencia',
            'certificate_hours' => rand(2, 16),
            'min_attendance_percentage' => 80,
            'location_type' => $data['event_type'],
            'physical_address' => $data['event_type'] !== 'virtual' ? fake('es_ES')->address : null,
            'room_number' => $data['event_type'] !== 'virtual' ? 'Sala ' . rand(1, 10) : null,
            'virtual_platform' => $data['event_type'] !== 'presencial' ? fake()->randomElement(['Zoom', 'Teams', 'Google Meet']) : null,
            'virtual_link' => $data['event_type'] !== 'presencial' ? 'https://meet.example.com/' . fake()->uuid : null,
            'waitlist_enabled' => fake()->boolean(30),
            'max_waitlist' => 20,
            'requires_approval' => false,
            'contact_email' => fake()->companyEmail,
            'contact_phone' => fake()->phoneNumber,
            'status' => 'publicado',
            'featured' => fake()->boolean(20),
            'published_at' => Carbon::now(),
            'allow_face_checkin' => true,
            'face_threshold' => 0.6,
        ], $data));
    }

    private function createUsers(): void
    {
        $this->command->info('👥 Creando 200 usuarios...');
        
        // Eliminar usuarios de tipo 'user' existentes (mantener admin y coordinator)
        User::where('role', 'user')->delete();
        
        $organizations = Organization::all();
        
        // Crear 200 usuarios
        // 60% con organización, 40% sin organización
        $usersWithOrg = 120;
        $usersWithoutOrg = 80;
        
        // Usuarios CON organización (distribuidos entre las organizaciones)
        $usersPerOrg = (int) ceil($usersWithOrg / $organizations->count());
        
        foreach ($organizations as $org) {
            User::factory()
                ->count($usersPerOrg)
                ->withOrganization($org->id)
                ->create();
        }
        
        // Usuarios SIN organización
        User::factory()
            ->count($usersWithoutOrg)
            ->create();
        
        $totalUsers = User::where('role', 'user')->count();
        $this->command->info("   ✓ {$totalUsers} usuarios tipo 'user' creados");
    }

    private function assignUsersToEvents(): void
    {
        $this->command->info('🔗 Asignando usuarios a eventos...');
        
        $users = User::where('role', 'user')->get();
        $events = Event::all();
        $assignments = 0;
        
        foreach ($users as $user) {
            // Cada usuario se inscribe a 2-5 eventos aleatorios
            $numEvents = rand(2, 5);
            $assignedEvents = [];
            
            // Filtrar eventos a los que el usuario puede acceder
            $accessibleEvents = $events->filter(function ($event) use ($user) {
                return $user->canAccessEvent($event);
            });
            
            if ($accessibleEvents->isEmpty()) {
                continue;
            }
            
            // Seleccionar eventos aleatorios
            $selectedEvents = $accessibleEvents->random(min($numEvents, $accessibleEvents->count()));
            
            foreach ($selectedEvents as $event) {
                if (!in_array($event->id, $assignedEvents)) {
                    // Verificar capacidad
                    $currentAttendees = $event->users()->count();
                    if ($event->capacity === null || $currentAttendees < $event->capacity) {
                        $user->events()->attach($event->id);
                        $assignedEvents[] = $event->id;
                        $assignments++;
                    }
                }
            }
        }
        
        $this->command->info("   ✓ {$assignments} inscripciones realizadas");
        
        // Estadísticas
        $this->printStatistics();
    }

    private function printStatistics(): void
    {
        $this->command->newLine();
        $this->command->info('📊 ESTADÍSTICAS FINALES:');
        $this->command->table(
            ['Métrica', 'Cantidad'],
            [
                ['Organizaciones', Organization::count()],
                ['Categorías', Category::count()],
                ['Eventos Totales', Event::count()],
                ['  - Eventos Públicos (inscripción abierta)', Event::where('is_open_enrollment', true)->count()],
                ['  - Eventos Privados (solo organización)', Event::where('is_open_enrollment', false)->count()],
                ['Usuarios Totales', User::count()],
                ['  - Usuarios con Organización', User::whereNotNull('organization_id')->count()],
                ['  - Usuarios sin Organización', User::whereNull('organization_id')->count()],
                ['Inscripciones (event_user)', DB::table('event_user')->count()],
            ]
        );
        
        $this->command->newLine();
        $this->command->info('🔑 Credenciales de prueba: [email] / password123');
    }
}

