<?php

namespace App\Http\Resources;

use App\Traits\HashIdTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    use HashIdTrait;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->encodeId($this->id ?? 1),
            'name' => $this->name,
            'tax_number' => $this->tax_number,
            'tax_rate' => $this->tax_rate,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
