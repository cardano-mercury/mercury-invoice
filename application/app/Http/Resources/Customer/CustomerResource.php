<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\{ Address, Email, Phone, CustomerCategory };

class CustomerResource extends JsonResource
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
            $categories = CustomerCategory::factory(1)->make();
            $defaultEmail = Email::factory()->make();
            $defaultPhone = Phone::factory()->make();
            $defaultAddress = Address::factory()->make();
        } else {
            $categories = $this->categories;
            $defaultEmail = $this->defaultEmail;
            $defaultPhone = $this->defaultPhone;
            $defaultAddress = $this->defaultAddress;
        }

        return [
            'id' => ($this->id ?? 1),
            'name' => $this->name,
            'tax_number' => $this->tax_number,
            'tax_rate' => $this->tax_rate,
            'categories' => CustomerCategoryResource::collection($categories),
            'default_email' => new CustomerEmailResource($defaultEmail),
            'default_phone' => new CustomerPhoneResource($defaultPhone),
            'default_address' => new CustomerAddressResource($defaultAddress),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
