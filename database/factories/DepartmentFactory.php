<?php

namespace Database\Factories;

use App\Models\Provincy;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Department>
 */
class DepartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $provincyCount = Provincy::count();

        return [
            'label' => fake()->country(),
            'provincy_id' => random_int(1, $provincyCount),
        ];
    }
}
