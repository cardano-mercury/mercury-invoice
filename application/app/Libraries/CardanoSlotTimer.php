<?php

namespace App\Libraries;

use App\Enums\CardanoNetwork;

class CardanoSlotTimer
{
    public static function slotToUnix($slot, CardanoNetwork $network): float|int
    {
        $slotConfig = self::getSlotConfig($network);
        $sAfterBegin = ($slot - $slotConfig['zeroSlot']) * $slotConfig['slotLength'];
        return $slotConfig['zeroTime'] + $sAfterBegin;
    }

    public static function unixToSlot($unixTime, CardanoNetwork $network): float|int
    {
        $slotConfig = self::getSlotConfig($network);
        $timePassed = $unixTime - $slotConfig['zeroTime'];
        $slotsPassed = floor($timePassed / $slotConfig['slotLength']);
        return $slotsPassed + $slotConfig['zeroSlot'];
    }

    private static function getSlotConfig(CardanoNetwork $network): array
    {
        return match ($network) {
            CardanoNetwork::MAINNET => [
                'zeroTime'   => 1596059091,
                'zeroSlot'   => 4492800,
                'slotLength' => 1,
            ],
            CardanoNetwork::PREVIEW => [
                'zeroTime'   => 1666656000,
                'zeroSlot'   => 0,
                'slotLength' => 1,
            ],
            CardanoNetwork::PREPROD => [
                'zeroTime'   => 1655769600,
                'zeroSlot'   => 86400,
                'slotLength' => 1,
            ],
        };
    }
}
