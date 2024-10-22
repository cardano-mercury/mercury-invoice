<?php

namespace App\Http\Requests\Customer;

use App\Enums\PhoneType;
use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerPhoneRequest extends FormRequest
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
                'in:' . implode(',', PhoneType::values()),
            ],
            'name' => [
                'required',
                'string',
                'min:3',
                'max:64',
            ],
            'number' => [
                'required',
                'string',
                'min:3',
                'max:32',
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
                'example' => PhoneType::random(),
            ],
            'name' => [
                'example' => 'John Doe',
            ],
            'number' => [
                'example' => '+1-956-745-2290',
            ],
            'is_default' => [
                'example' => true,
            ],
        ];
    }
}
