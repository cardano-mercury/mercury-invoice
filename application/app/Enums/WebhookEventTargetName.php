<?php

namespace App\Enums;

use App\Traits\EnumToArrayTrait;

enum WebhookEventTargetName: string
{
    use EnumToArrayTrait;

    case INVOICE_PAID = 'Invoice Paid';
}
