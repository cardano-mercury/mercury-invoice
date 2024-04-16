<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $unitTypes = ['kg', 'gram', 'cm', 'ounce', 'each'];

        return [
            'user_id' => 0,
            'name' => ucwords(implode(' ', fake()->unique()->words())),
            'sku' => strtoupper(Str::random()),
            'description' => fake()->sentences(3, true),
            'unit_type' => $unitTypes[array_rand($unitTypes)],
            'unit_price' => fake()->randomFloat(2, random_int(2, 5), random_int(50, 100)),
            'supplier' => fake()->company(),
        ];
    }
}
