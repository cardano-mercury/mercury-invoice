<?php

namespace App\Enums;

use App\Traits\EnumToArrayTrait;

enum AddressType: string
{
    use EnumToArrayTrait;

    case BILLING = 'Billing';
    case SHIPPING = 'Shipping';
}
