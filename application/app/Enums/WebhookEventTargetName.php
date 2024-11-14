<?php

namespace App\Enums;

use App\Traits\EnumToArrayTrait;

enum WebhookEventTargetName: string
{
    use EnumToArrayTrait;

    case CUSTOMER_CREATED = 'Customer Created';
    case CUSTOMER_UPDATED = 'Customer Updated';

    case PRODUCT_CREATED = 'Product Created';
    case PRODUCT_UPDATED = 'Product Updated';

    case SERVICE_CREATED = 'Service Created';
    case SERVICE_UPDATED = 'Service Updated';

    case INVOICE_CREATED = 'Invoice Created';
    case INVOICE_UPDATED = 'Invoice Updated';
    case INVOICE_PUBLISHED = 'Invoice Published';
    case INVOICE_VOIDED = 'Invoice Voided';
    case INVOICE_RESTORED = 'Invoice Restored';
    case INVOICE_PAID = 'Invoice Paid';
    case INVOICE_MANUALLY_MARKED_AS_PAID = 'Invoice Manually Marked As Paid';
}
