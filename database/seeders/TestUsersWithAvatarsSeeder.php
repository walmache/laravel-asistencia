<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder para crear usuarios de prueba con avatares
 * Las imágenes deben existir en storage/app/public/avatars/
 */
class TestUsersWithAvatarsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testUsers = [
            [
                'name' => 'María García López',
                'email' => 'maria.garcia@example.com',
                'password' => Hash::make('password123'),
                'role' => 'coordinator',
                'face_image_path' => 'avatars/avatar1.jpg',
            ],
            [
                'name' => 'Carlos Rodríguez Pérez',
                'email' => 'carlos.rodriguez@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'face_image_path' => 'avatars/avatar2.jpg',
            ],
            [
                'name' => 'Ana Martínez Sánchez',
                'email' => 'ana.martinez@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'face_image_path' => 'avatars/avatar3.jpg',
            ],
            [
                'name' => 'Pedro López Fernández',
                'email' => 'pedro.lopez@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'face_image_path' => 'avatars/avatar4.jpg',
            ],
            [
                'name' => 'Laura Hernández Díaz',
                'email' => 'laura.hernandez@example.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'face_image_path' => 'avatars/avatar5.jpg',
            ],
        ];

        foreach ($testUsers as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }

        $this->command->info('✅ 5 usuarios de prueba con avatares creados exitosamente.');
        $this->command->info('   Credenciales: [email] / password123');
    }
}

