<?php

namespace Database\Factories;

use App\Models\Phone;
use App\Enums\PhoneType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Phone>
 */
class PhoneFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => 0,
            'type' => PhoneType::random(),
            'name' => fake()->name(),
            'number' => fake()->unique()->phoneNumber(),
            'is_default' => true,
        ];
    }
}
