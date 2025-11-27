<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Category;
use App\Models\Organization;
use Illuminate\Database\Seeder;

class TwentyEventsSeeder extends Seeder
{
    public function run(): void
    {
        // Asegurar que existan categorías y organizaciones
        if (Category::count() == 0) {
            $this->call(ComprehensiveEventSeeder::class);
        }
        
        if (Organization::count() == 0) {
            $this->call(OrganizationsSeeder::class);
        }

        $this->command->info('Generando 20 eventos variados...');

        // Generar 20 eventos usando el factory
        Event::factory()
            ->count(20)
            ->create();

        $this->command->info('✅ 20 eventos creados exitosamente.');
    }
}


