<?php

namespace App\Enums;

use App\Traits\EnumToArrayTrait;

enum ReportType: string
{
    use EnumToArrayTrait;

    case AGING_RECONCILIATION = 'Aging Reconciliation';
    case REVENUE_BY_PRODUCT = 'Revenue by Product';
    case REVENUE_BY_SERVICE = 'Revenue by Service';
    case REVENUE_BY_CUSTOMER = 'Revenue by Customer';
    case TOTAL_REVENUE_BY_PERIOD = 'Total Revenue by Period';
}
