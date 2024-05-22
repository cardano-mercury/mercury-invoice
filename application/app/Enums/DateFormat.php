<?php

namespace App\Enums;

use App\Traits\EnumToArrayTrait;

enum DateFormat: string
{
    use EnumToArrayTrait;

    case DATE_FORMAT = 'jS F, Y';
    case DATE_FORMAT_SHORT = 'jS F';
    case DATE_FORMAT_WITH_TIME = 'H:i A \o\n jS F, Y';
}
