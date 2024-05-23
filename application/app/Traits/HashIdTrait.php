<?php

namespace App\Traits;

use Vinkla\Hashids\Facades\Hashids;

trait HashIdTrait
{
    public function encodeId(int|null $id = null): string|null
    {
        if (!empty($id)) {
            return Hashids::encode($id);
        }

        return null;
    }

    public function decodeId(string|null $encodedId = null): int
    {
        if (!empty($encodedId)) {
            return Hashids::decode($encodedId)[0] ?? 0;
        }

        return 0;
    }
}
