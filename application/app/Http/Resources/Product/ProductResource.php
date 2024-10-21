<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => ($this->id ?? 1),
            'name' => $this->name,
            'sku' => $this->sku,
            'description' => $this->description,
            'unit_type' => $this->unit_type,
            'unit_price' => $this->unit_price,
            'supplier' => $this->supplier,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
