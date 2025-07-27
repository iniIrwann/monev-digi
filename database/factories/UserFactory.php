<?php

namespace Database\Factories;

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
            'name' => fake()->name(),
            // 'username' => fake()->unique()->userName(),
            'username' => fake()->unique()->randomElement([
                'admin1',
                'admin2',
                'admin3',
            ]),
            'role' => 'admin', // Atau bisa: fake()->randomElement(['admin', 'user'])
            'nohp' => fake()->phoneNumber(),
            'desa' => fake()->randomElement(['Desa Cileunyi', 'Desa Cipacing']),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('pass'),
            'remember_token' => Str::random(10),
        ];

    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
        ]);
    }
}
