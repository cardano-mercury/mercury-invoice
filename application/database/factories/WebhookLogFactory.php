<?php

namespace Database\Factories;

use App\Enums\Status;
use App\Models\WebhookLog;
use Random\RandomException;
use App\Enums\WebhookEventTargetName;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WebhookLog>
 */
class WebhookLogFactory extends Factory
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
            'webhook_id' => 0,
            'status' => Status::SUCCESS,
            'event_target_id' => 1,
            'event_name' => WebhookEventTargetName::INVOICE_PAID,
            'payload' => json_encode([
                'invoice_uuid' => fake()->uuid(),
                'payment_method' => 'ADA',
                'amount' => fake()->randomFloat(2, 100),
            ]),
            'attempts' => random_int(1, 10),
        ];
    }
}
