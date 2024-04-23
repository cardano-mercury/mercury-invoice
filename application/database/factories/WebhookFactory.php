<?php

namespace Database\Factories;

use App\Models\Webhook;
use Illuminate\Support\Str;
use App\Enums\HMACAlgorithm;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Webhook>
 */
class WebhookFactory extends Factory
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
            'url' => 'https://webhook.site/#!/view/dc966757-e77e-4cc8-92cf-d539ec208f48',
            'hmac_secret' => encrypt(Str::random(128)),
            'hmac_algorithm' => HMACAlgorithm::random(),
            'max_attempts' => 10,
            'timeout_seconds' => 30,
            'retry_seconds' => 30,
            'enabled' => true,
        ];
    }
}
