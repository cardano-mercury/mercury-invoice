<?php

namespace App\Enums;

enum AddressType: string
{
    case BILLING = 'Billing';
    case SHIPPING = 'Shipping';
}
