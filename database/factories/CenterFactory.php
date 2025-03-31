<?php

namespace Database\Factories;

use App\Models\Locality;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Center>
 */
class CenterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $localityCount = Locality::count();

        return [
            'label' => fake()->country(),
            'code' => fake()->countryCode(),
            'office' => random_int(10, 100),
            'locality_id' => random_int(1, $localityCount),
        ];
    }
}
