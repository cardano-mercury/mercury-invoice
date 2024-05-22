<?php

namespace Database\Factories;

use App\Models\Service;
use Random\RandomException;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * @throws RandomException
     */
    public function definition(): array
    {
        return [
            'user_id' => 0,
            'name' => ucwords(implode(' ', fake()->unique()->words())),
            'description' => fake()->sentences(3, true),
            'unit_price' => fake()->randomFloat(2, random_int(2, 5), random_int(50, 100)),
            'supplier' => fake()->company(),
        ];
    }
}
