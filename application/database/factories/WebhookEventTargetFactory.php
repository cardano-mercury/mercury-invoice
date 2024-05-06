<?php

namespace Database\Factories;

use App\Models\WebhookEventTarget;
use App\Enums\WebhookEventTargetName;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WebhookEventTarget>
 */
class WebhookEventTargetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'webhook_id' => 0,
            'event_name' => WebhookEventTargetName::INVOICE_PAID,
        ];
    }
}
