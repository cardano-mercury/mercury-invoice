<?php

namespace App\Enums;

use App\Traits\EnumToArrayTrait;

enum Status: string
{
    use EnumToArrayTrait;

    case PENDING = 'Pending';
    case SUCCESS = 'Success';
    case ERROR = 'Error';
    case TIMED_OUT = 'Timed Out';

    case DRAFT = 'Draft';
    case PUBLISHED = 'Published';
    case PAID = 'Paid';
    case VOIDED = 'Voided';
}
