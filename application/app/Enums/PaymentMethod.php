<?php

namespace App\Enums;

use App\Traits\EnumToArrayTrait;

enum PaymentMethod: string
{
    use EnumToArrayTrait;

    case STRIPE = 'Stripe';
    case CRYPTO = 'Crypto';
}
