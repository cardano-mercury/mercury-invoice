<?php

namespace App\Traits;

use Vinkla\Hashids\Facades\Hashids;

trait HashIdTrait
{
    public function encodeId(int $id): string
    {
        return Hashids::encode($id);
    }

    public function decodeId(string $encodedId): int
    {
        return Hashids::decode($encodedId)[0] ?? 0;
    }
}
