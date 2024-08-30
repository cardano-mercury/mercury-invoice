<?php

namespace App\Enums;

use App\Traits\EnumToArrayTrait;

enum CardanoNetwork: string
{
    use EnumToArrayTrait;

    case MAINNET = 'Cardano Mainnet';
    case PREPROD = 'Cardano PreProd';
    case PREVIEW = 'Cardano Preview';
}
