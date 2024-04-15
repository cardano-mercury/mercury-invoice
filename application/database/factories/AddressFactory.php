<?php

namespace Database\Factories;

use App\Models\Address;
use App\Enums\AddressType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $localisedFaker = \Faker\Factory::create('en_US');

        return [
            'customer_id' => 0,
            'type' => AddressType::BILLING,
            'name' => fake()->company(),
            'line1' => $localisedFaker->streetAddress(),
            'line2' => null,
            'city' => $localisedFaker->city(),
            'state' => $localisedFaker->state,
            'postal_code' => $localisedFaker->postcode(),
            'country' => 'United States',
            'is_default' => true,
        ];
    }
}
