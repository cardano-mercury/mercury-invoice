<?php

namespace App\Traits;

trait EnumToArrayTrait
{
    public static function names(): array
    {
        $names = [];
        foreach (self::cases() as $case) {
            $names[] = $case->name;
        }
        return $names;
    }

    public static function values(): array
    {
        $values = [];
        foreach (self::cases() as $case) {
            $values[] = $case->value;
        }
        return $values;
    }

    public static function random(): mixed
    {
        return self::values()[array_rand(self::values())];
    }

    public static function array(): array
    {
        return array_combine(self::values(), self::names());
    }
}
