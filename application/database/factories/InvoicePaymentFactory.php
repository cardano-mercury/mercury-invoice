<?php

namespace Database\Factories;

use App\Enums\Status;
use Illuminate\Support\Str;
use App\Models\InvoicePayment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InvoicePayment>
 */
class InvoicePaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'invoice_id' => 0,
            'payment_date' => now()->toDateString(),
            'payment_method' => 'Stripe',
            'payment_currency' => 'USD',
            'payment_amount' => $this->faker->randomFloat(2, 10),
            'payment_reference' => Str::random(),
            'crypto_asset_name' => null,
            'crypto_asset_ada_price' => null,
            'crypto_asset_quantity' => null,
            'status' => Status::PENDING,
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString(),
        ];
    }
}
