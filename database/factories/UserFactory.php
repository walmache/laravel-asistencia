<?php

namespace Database\Factories;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake('es_ES')->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password123'),
            'role' => 'user',
            'organization_id' => null,
            'face_image_path' => null,
            'consent_face_processing' => fake()->boolean(70),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Usuario con organización asignada
     */
    public function withOrganization(?int $organizationId = null): static
    {
        return $this->state(fn (array $attributes) => [
            'organization_id' => $organizationId ?? Organization::inRandomOrder()->first()?->id,
        ]);
    }

    /**
     * Usuario administrador
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }

    /**
     * Usuario coordinador
     */
    public function coordinator(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'coordinator',
        ]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
