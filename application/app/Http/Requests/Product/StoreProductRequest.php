<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'PUT' => $this->product->user_id === auth()->id(),
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
            'sku' => [
                'nullable',
                'max:32',
            ],
            'description' => [
                'nullable',
            ],
            'unit_type' => [
                'nullable',
                'max:16',
            ],
            'unit_price' => [
                'required',
                'min:0',
            ],
            'supplier' => [
                'nullable',
                'max:64',
            ],
        ];
    }
}
