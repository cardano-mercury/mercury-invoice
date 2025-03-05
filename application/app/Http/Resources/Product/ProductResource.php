<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use App\Models\ProductCategory;
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
        /**
         * NOTE: This is a hacky workaround to ensure Scribe api documentation package generates
         * correct example response for nested relationship, when generating api docs.
         */
        if ($request->server('SERVER_NAME') === 'localhost' &&
            $request->server('HTTP_USER_AGENT') === 'Symfony' &&
            $request->method() !== 'POST'
        ) {
            $categories = ProductCategory::factory(1)->make();
        } else {
            $categories = $this->categories;
        }

        return [
            'id' => ($this->id ?? 1),
            'name' => $this->name,
            'sku' => $this->sku,
            'description' => $this->description,
            'unit_type' => $this->unit_type,
            'unit_price' => (float) $this->unit_price,
            'supplier' => $this->supplier,
            'categories' => ProductCategoryResource::collection($categories),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
