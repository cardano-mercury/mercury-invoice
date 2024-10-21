<?php

namespace App\Http\Requests\Product;

use Illuminate\Validation\Rule;
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
            'PUT', 'PATCH' => $this->product->user_id === auth()->id(),
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
                Rule::unique('customers', 'name')->where(function($query) {
                    return $query
                        ->where('user_id', auth()->id())
                        ->where('name', $this->name);
                })->ignore($this?->product?->id),
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
                'numeric',
                'min:0',
            ],
            'supplier' => [
                'nullable',
                'max:64',
            ],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'name' => [
                'example' => 'Website Hosting',
            ],
            'sku' => [
                'example' => 'WH-001',
            ],
            'description' => [
                'example' => 'Standard website hosting on shared server',
            ],
            'unit_type' => [
                'example' => 'Each',
            ],
            'unit_price' => [
                'example' => 12.99,
            ],
            'supplier' => [
                'example' => 'Drip Dropz Ltd',
            ],
        ];
    }
}
