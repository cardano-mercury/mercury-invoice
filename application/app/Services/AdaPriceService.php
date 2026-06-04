<?php

namespace App\Services;

use Illuminate\Http\Client\Pool;
use App\Enums\AdaPriceFeedSource;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class AdaPriceService
{
    private const int DEFAULT_CACHE_TTL_SECONDS = 300;
    private const int DEFAULT_TIMEOUT_SECONDS = 10;

    public static function convert(string $currency): float
    {
        return Cache::remember('ada-price:' . $currency, self::DEFAULT_CACHE_TTL_SECONDS, function () use ($currency) {

            $responses = Http::timeout(self::DEFAULT_TIMEOUT_SECONDS)
                ->connectTimeout(self::DEFAULT_TIMEOUT_SECONDS)
                ->pool(fn (Pool $pool) => [
                    $pool->as(AdaPriceFeedSource::COINGECKO->value)->get('https://api.coingecko.com/api/v3/simple/price?ids=cardano&vs_currencies=' . strtolower($currency)),
                    $pool->as(AdaPriceFeedSource::HITBTC->value)->get('https://api.hitbtc.com/api/2/public/ticker/ADA' . strtoupper($currency)),
                    $pool->as(AdaPriceFeedSource::COINBASE->value)->get('https://api.coinbase.com/v2/prices/ADA-' . strtoupper($currency) . '/spot'),
                ]);

            $priceFeedResults = [];
            foreach (AdaPriceFeedSource::values() as $adaPriceFeedSource) {
                if (isset($responses[$adaPriceFeedSource]) && $responses[$adaPriceFeedSource]->ok()) {
                    $currencyValue = match ($adaPriceFeedSource) {
                        AdaPriceFeedSource::COINGECKO->value => (float) $responses[$adaPriceFeedSource]->json('cardano.' . strtolower($currency)),
                        AdaPriceFeedSource::HITBTC->value => (float) $responses[$adaPriceFeedSource]->json('last'),
                        AdaPriceFeedSource::COINBASE->value => (float) $responses[$adaPriceFeedSource]->json('data.amount'),
                        default => 0,
                    };
                    if ($currencyValue > 0) {
                        $priceFeedResults[] = $currencyValue;
                    }
                }
            }

            if ($priceFeedResultCount = count($priceFeedResults)) {
                return round(array_sum($priceFeedResults) / $priceFeedResultCount, 6);
            }

            return null;

        });
    }
}
