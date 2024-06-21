<?php

namespace Database\Factories;

use Random\RandomException;
use Illuminate\Support\Str;
use App\Models\InvoiceItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InvoiceItem>
 */
class InvoiceItemFactory extends Factory
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
            'invoice_id' => 0,
            'product_id' => null,
            'service_id' => null,
            'sku' => strtoupper(Str::random()),
            'description' => fake()->sentence(),
            'quantity' => fake()->numberBetween(2, 6),
            'unit_price' => fake()->randomFloat(2, random_int(2, 5), random_int(50, 100)),
            'tax_rate' => 20.00,
        ];
    }
}
