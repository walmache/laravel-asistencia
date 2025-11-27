<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Event;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $startDate = Carbon::instance($this->faker->dateTimeBetween('now', '+6 months'));
        $endDate = $startDate->copy()->addHours($this->faker->numberBetween(2, 48));
        
        $isFree = $this->faker->boolean(30); // 30% chance de ser gratuito
        $eventType = $this->faker->randomElement(['presencial', 'virtual', 'hibrido']);
        $locationType = $eventType;

        return [
            'organization_id' => Organization::inRandomOrder()->first()?->id ?? Organization::factory(),
            'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'title' => $this->faker->sentence(4),
            'short_description' => $this->faker->text(150),
            'description' => $this->faker->paragraphs(3, true),
            'event_type' => $eventType,
            
            // Fechas
            'start_date' => $startDate,
            'end_date' => $endDate,
            'registration_start' => Carbon::now(),
            'registration_deadline' => $startDate->copy()->subDays(1),
            'early_bird_deadline' => $startDate->copy()->subMonths(1),
            
            // Precios
            'is_free' => $isFree,
            'price' => $isFree ? 0 : $this->faker->randomFloat(2, 10, 500),
            'currency' => 'USD',
            'early_bird_price' => $isFree ? 0 : $this->faker->randomFloat(2, 5, 400),
            'group_price' => $isFree ? 0 : $this->faker->randomFloat(2, 8, 450),
            'max_group_size' => 5,
            
            // Certificación
            'provides_certificate' => $this->faker->boolean(80),
            'certificate_type' => 'Certificado de Asistencia',
            'certificate_hours' => $this->faker->numberBetween(2, 40),
            'min_attendance_percentage' => 80,
            
            // Ubicación
            'location_type' => $locationType,
            'physical_address' => ($locationType !== 'virtual') ? $this->faker->address : null,
            'room_number' => ($locationType !== 'virtual') ? 'Sala ' . $this->faker->randomLetter : null,
            'virtual_platform' => ($locationType !== 'presencial') ? $this->faker->randomElement(['Zoom', 'Teams', 'Google Meet']) : null,
            'virtual_link' => ($locationType !== 'presencial') ? $this->faker->url : null,
            'virtual_password' => ($locationType !== 'presencial') ? $this->faker->password(8) : null,
            
            // Capacidad
            'capacity' => $this->faker->numberBetween(20, 500),
            'waitlist_enabled' => $this->faker->boolean(),
            'max_waitlist' => 20,
            'requires_approval' => $this->faker->boolean(20),
            
            // Contacto
            'contact_email' => $this->faker->companyEmail,
            'contact_phone' => $this->faker->phoneNumber,
            
            // Estado
            'status' => 'publicado',
            'is_public' => true,
            'featured' => $this->faker->boolean(10),
            'published_at' => Carbon::now()->subDays(5),
            
            // Políticas
            'cancellation_policy' => 'Cancelaciones permitidas hasta 48 horas antes del evento.',
            'refund_policy' => 'Reembolso del 100% si se cancela con 7 días de anticipación.',
            'terms_conditions' => 'Al registrarse acepta los términos y condiciones del evento.',
        ];
    }
}


