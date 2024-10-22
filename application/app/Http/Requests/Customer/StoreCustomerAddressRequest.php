<?php

namespace App\Http\Requests\Customer;

use App\Enums\AddressType;
use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return match($this->method()) {
            // Anyone can create new record
            'POST' => true,
            // Updating must match record owner
            'PUT', 'PATCH' => $this->customer->user_id === auth()->id(),
            // Unauthorized for everything else
            default => false,
        };
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'type' => [
                'required',
                'in:' . implode(',', AddressType::values()),
            ],
            'name' => [
                'required',
                'string',
                'min:3',
                'max:64',
            ],
            'line1' => [
                'required',
                'string',
                'min:3',
                'max:64',
            ],
            'line2' => [
                'nullable',
                'string',
                'min:3',
                'max:64',
            ],
            'city' => [
                'required',
                'string',
                'min:3',
                'max:64',
            ],
            'state' => [
                'nullable',
                'string',
                'min:3',
                'max:64',
            ],
            'postal_code' => [
                'required',
                'string',
                'min:3',
                'max:64',
            ],
            'country' => [
                'required',
                'string',
                'min:3',
                'max:64',
            ],
            'is_default' => [
                'nullable',
                'boolean',
            ],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'type' => [
                'example' => AddressType::random(),
            ],
            'name' => [
                'example' => 'John Doe',
            ],
            'line1' => [
                'example' => '2592 Jada Key',
            ],
            'line2' => [
                'example' => 'Apartment 504',
            ],
            'city' => [
                'example' => 'South Josh',
            ],
            'state' => [
                'example' => 'Kentucky',
            ],
            'postal_code' => [
                'example' => '62249-8453',
            ],
            'country' => [
                'example' => 'United States',
            ],
            'is_default' => [
                'example' => true,
            ],
        ];
    }
}
