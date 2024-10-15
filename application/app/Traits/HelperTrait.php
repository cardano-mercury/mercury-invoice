<?php

namespace App\Traits;

trait HelperTrait
{
    function clamp(int|null $current, $min, $max) {
        return max($min, min($max, $current));
    }
}
