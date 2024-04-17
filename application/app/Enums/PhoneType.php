<?php

namespace App\Enums;

use App\Traits\EnumToArrayTrait;

enum PhoneType: string
{
    use EnumToArrayTrait;

    CASE HOME = 'Home';
    CASE OFFICE = 'Office';
    CASE MOBILE = 'Mobile';
}
