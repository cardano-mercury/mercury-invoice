<?php

namespace Database\Factories;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 0,
            'customer_id' => 0,
            'billing_address_id' => 0,
            'shipping_address_id' => 0,
            'currency' => 'USD',
            'customer_reference' => 'INV-' . fake()->unique()->numberBetween(10000000, 99999999),
            'issue_date' => now()->toDateString(),
            'due_date' => now()->addDays(30)->toDateString(),
            'last_notified' => null,
            'status' => '',
        ];
    }
}
