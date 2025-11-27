<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;

/**
 * Seeder para crear organizaciones de ejemplo
 */
class OrganizationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizations = [
            [
                'name' => 'TechCorp Ecuador',
                'ruc' => '1791234567001',
                'business_name' => 'TechCorp Ecuador S.A.',
                'description' => 'Empresa líder en soluciones tecnológicas y desarrollo de software para empresas.',
                'address' => 'Av. República E7-123 y Almagro, Edificio Platinum, Piso 8, Quito',
                'phone' => '+593 2 234 5678',
                'email' => 'info@techcorp.ec',
                'legal_rep_id' => '1712345678',
                'legal_rep_name' => 'Carlos Alberto Mendoza Pérez',
            ],
            [
                'name' => 'Innovación Digital',
                'ruc' => '0991234567001',
                'business_name' => 'Innovación Digital Cía. Ltda.',
                'description' => 'Consultora especializada en transformación digital y automatización de procesos.',
                'address' => 'Av. 9 de Octubre 1234 y Boyacá, Torre Empresarial, Oficina 502, Guayaquil',
                'phone' => '+593 4 567 8901',
                'email' => 'contacto@innovaciondigital.com',
                'legal_rep_id' => '0912345678',
                'legal_rep_name' => 'María Fernanda López Rodríguez',
            ],
            [
                'name' => 'Universidad Central',
                'ruc' => '1760001234001',
                'business_name' => 'Universidad Central del Ecuador',
                'description' => 'Institución de educación superior pública con más de 190 años de historia.',
                'address' => 'Av. América y Av. Universitaria, Ciudadela Universitaria, Quito',
                'phone' => '+593 2 252 6810',
                'email' => 'info@uce.edu.ec',
                'legal_rep_id' => '1701234567',
                'legal_rep_name' => 'Dr. Fernando Sempertegui Ontaneda',
            ],
            [
                'name' => 'Eventos Premium',
                'ruc' => '1792345678001',
                'business_name' => 'Eventos Premium Ecuador S.A.S.',
                'description' => 'Organización de eventos corporativos, conferencias y capacitaciones empresariales.',
                'address' => 'Calle Whymper N27-45 y Orellana, Quito',
                'phone' => '+593 99 123 4567',
                'email' => 'eventos@premiuecuador.com',
                'legal_rep_id' => '1723456789',
                'legal_rep_name' => 'Andrea Paola Gutiérrez Sánchez',
            ],
            [
                'name' => 'Cámara de Comercio Quito',
                'ruc' => '1790012345001',
                'business_name' => 'Cámara de Comercio de Quito',
                'description' => 'Gremio empresarial que representa y defiende los intereses del sector comercial.',
                'address' => 'Av. Amazonas N36-105 y Juan Pablo Sanz, Quito',
                'phone' => '+593 2 243 5678',
                'email' => 'info@ccq.ec',
                'legal_rep_id' => '1709876543',
                'legal_rep_name' => 'Patricio Alarcón Espinosa',
            ],
        ];

        foreach ($organizations as $orgData) {
            Organization::updateOrCreate(
                ['ruc' => $orgData['ruc']],
                $orgData
            );
        }

        $this->command->info('✅ 5 organizaciones de ejemplo creadas exitosamente.');
    }
}


