<?php

namespace Database\Factories;

use App\Data\Profile;
use App\Models\Center;
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
        $centerCount = Center::count();

        return [
            'username' => fake()->randomNumber(5),
            'name' => fake()->name(),
            'firstname' => fake()->firstName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('123456789012'),
            'remember_token' => Str::random(10),
            'is_locked' => random_int(0, 1),
            'center_id' => random_int(1, $centerCount),
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
            'username' => '12345',
            'is_locked' => 0,
            'profile' => Profile::ADMIN,
        ]);
    }

    public function representant(): static
    {
        return $this->state(fn (array $attributes) => [
            'username' => '56789',
            'is_locked' => 0,
            'profile' => Profile::REPRESENTANT,
        ]);
    }

    public function consultant(): static
    {
        return $this->state(fn (array $attributes) => [
            'username' => '01234',
            'is_locked' => 0,
            'profile' => Profile::CONSULTANT,
        ]);
    }

    public function president(): static
    {
        return $this->state(fn (array $attributes) => [
            'username' => 'PRESIDENT@2025',
            'is_locked' => 0,
            'profile' => Profile::PRESIDENT,
        ]);
    }
}
