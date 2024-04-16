<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum PhoneType: string
{
    use EnumToArray;

    CASE HOME = 'Home';
    CASE OFFICE = 'Office';
    CASE MOBILE = 'Mobile';
}
