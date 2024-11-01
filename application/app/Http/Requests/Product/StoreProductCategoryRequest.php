<?php

namespace App\Http\Requests\Product;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductCategoryRequest extends FormRequest
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
            'PUT', 'PATCH' => $this->product_category->user_id === auth()->id(),
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
                'string',
                'min:3',
                'max:64',
                Rule::unique('product_categories', 'name')->where(function($query) {
                    return $query
                        ->where('user_id', auth()->id())
                        ->where('name', $this->name);
                })->ignore($this?->product_category?->id),
            ],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'name' => [
                'example' => 'Hosting',
            ],
        ];
    }
}
