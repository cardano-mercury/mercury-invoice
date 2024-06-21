<?php

namespace Database\Factories;

use App\Models\InvoiceActivity;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InvoiceActivity>
 */
class InvoiceActivityFactory extends Factory
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
            'activity' => 'Viewed Invoice',
        ];
    }
}
