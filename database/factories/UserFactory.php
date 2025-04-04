<?php

namespace Database\Factories;

use App\Data\Profile;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
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
     * @throws Exception
     */
    public function definition(): array
    {

        return [
            'username' => fake()->randomNumber(5),
            'name' => fake()->name(),
            'firstname' => fake()->firstName(),
            'email' => fake()->unique()->safeEmail(),
            'office' => "Aucun Bureau",
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('123456789012'),
            'remember_token' => Str::random(10),
            'is_locked' => random_int(0, 1),
            'profile' => fake()->randomElement(Profile::getCodes()),
        ];
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

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'username' => 'ADMIN',
            'firstname' => 'ADMIN@2025',
            'password' => Hash::make('ADMIN@2025'),
            'is_locked' => 0,
            'profile' => Profile::ADMIN,
        ]);
    }

    public function representant(): static
    {
        return $this->state(fn (array $attributes) => [
            'username' => 'REPRESENTANT',
            'password' => Hash::make('REPRESENTANT@2025'),
            'firstname' => 'REPRESENTANT@2025',
            'is_locked' => 0,
            'profile' => Profile::REPRESENTANT
        ]);
    }
    public function representantExterne(): static
    {
        return $this->state(fn (array $attributes) => [
            'username' => 'REPRESENTANT_01',
            'password' => Hash::make('REPRESENTANT_01@2025'),
            'firstname' => 'REPRESENTANT_01@2025',
            'is_locked' => 0,
            'profile' => Profile::REPRESENTANT
        ]);
    }

    public function consultant(): static
    {
        return $this->state(fn (array $attributes) => [
            'username' => 'CONSULTANT',
            'password' => Hash::make('CONSULTANT@2025'),
            'firstname' => 'CONSULTANT@2025',
            'is_locked' => 0,
            'profile' => Profile::CONSULTANT
        ]);
    }

    public function president(): static
    {
        return $this->state(fn (array $attributes) => [
            'username' => 'PRESIDENT',
            'name' => 'Brice Clotaire OLIGUI NGUEMA',
            'password' => Hash::make('PRESIDENT@2025'),
            'firstname' => 'PRESIDENT@2025',
            'is_locked' => 0,
            'profile' => Profile::PRESIDENT
        ]);
    }
}
