<?php

namespace Database\Factories;

use App\Enums\Status;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Invoice>
 */
class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $invoiceDate = $this->faker->dateTimeBetween('-365 days', 'now');

        return [
            'user_id' => 0,
            'customer_id' => 0,
            'billing_address_id' => 0,
            'shipping_address_id' => 0,
            'currency' => 'USD',
            'customer_reference' => 'INV-' . fake()->unique()->numberBetween(10000000, 99999999),
            'issue_date' => $invoiceDate,
            'due_date' => Carbon::createFromFormat('Y-m-d H:i:s', $invoiceDate->format('Y-m-d H:i:s'))->addDays(30)->toDateString(),
            'last_notified' => null,
            'status' => Status::random([
                Status::DRAFT->value,
                Status::PUBLISHED->value,
                Status::PAYMENT_PROCESSING->value,
                Status::VOIDED->value,
            ]),
            'created_at' => $invoiceDate,
            'updated_at' => $invoiceDate,
        ];
    }
}
