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
    case GENERATING = 'Generating';

    case DRAFT = 'Draft';
    case PUBLISHED = 'Published';
    case PAYMENT_PROCESSING = 'Payment Processing';
    case PAID = 'Paid';
    case VOIDED = 'Voided';
}
