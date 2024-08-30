<?php

use App\Enums\CardanoNetwork;

return [

    'app_version' => '0.0.1',

    'supported_currencies' => [
        // *Note* all of these currencies have 2 decimal places, i.e. 1 USD = 100 Cent (Expected format in Stripe)
        'USD' => 'US Dollar (USD)',
        'EUR' => 'Euro (EUR)',
        'GBP' => 'Pound Sterling (GBP)',
        'AUD' => 'Australian Dollar (AUD)',
        'CAD' => 'Canadian Dollar (CAD)',
        'HKD' => 'Hong Kong Dollar (HKD)',
        'NZD' => 'New Zealand Dollar (NZD)',
    ],

    'target_cardano_network' => env('APP_ENV') === 'production' ? CardanoNetwork::MAINNET : CardanoNetwork::PREPROD,

    'crypto_payment_deadline_seconds' => 3600,

];
