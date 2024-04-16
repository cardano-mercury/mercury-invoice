<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum AddressType: string
{
    use EnumToArray;

    case BILLING = 'Billing';
    case SHIPPING = 'Shipping';
}
