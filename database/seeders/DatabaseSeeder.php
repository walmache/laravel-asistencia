<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Organization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default organization
        $organization = Organization::firstOrCreate(
            ['name' => 'Default Organization'],
            ['description' => 'Default organization for the system']
        );

        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('Rtl8139$'),
                'role' => 'admin',
            ]
        );
        $admin->password = Hash::make('Rtl8139$');
        $admin->save();

        // Create coordinator user
        $coordinator = User::firstOrCreate(
            ['email' => 'coordinator@example.com'],
            [
                'name' => 'Coordinator',
                'password' => Hash::make('Rtl8139$'),
                'role' => 'coordinator',
            ]
        );
        $coordinator->password = Hash::make('Rtl8139$');
        $coordinator->save();

        // Create regular user
        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('Rtl8139$'),
                'role' => 'user',
            ]
        );
        $user->password = Hash::make('Rtl8139$');
        $user->save();
    }
}
