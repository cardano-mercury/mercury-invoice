<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
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
            'PUT' => $this->customer->user_id === auth()->id(),
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
            'name' => [
                'required',
                'min:3',
                'max:64',
            ],
            'tax_number' => [
                'nullable',
                'max:64',
            ],
            'tax_rate' => [
                'nullable',
                'min:0',
                'max:100.00',
            ],
        ];
    }
}
