<?php

namespace App\Enums;

use App\Traits\EnumToArrayTrait;

enum AdaPriceFeedSource: string
{
    use EnumToArrayTrait;

    case COINGECKO = 'CoinGecko';
    case HITBTC = 'HitBTC';
    case COINBASE = 'CoinBase';
}
