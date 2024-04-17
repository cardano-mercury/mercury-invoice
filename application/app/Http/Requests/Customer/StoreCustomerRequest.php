<?php

namespace App\Http\Requests\Customer;

use Illuminate\Support\Facades\Auth;
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
            // Updating & Deleting must match record owner
            'PUT', 'DELETE' => $this->customer->user_id === Auth::id(),
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
                'max:64',
            ],
            'tax_number' => [
                'max:64',
            ],
            'tax_rate'   => [
                'min:0',
                'max:100.00',
            ],
        ];
    }
}
