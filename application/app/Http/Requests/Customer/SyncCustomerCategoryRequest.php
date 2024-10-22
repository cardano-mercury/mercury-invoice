<?php

namespace App\Http\Requests\Customer;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SyncCustomerCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'customer_id' => [
                'required',
                'integer',
                Rule::exists('customers', 'id')->where(function ($query) {
                    return $query
                        ->where('user_id', auth()->id())
                        ->where('id', $this->customer_id);
                })
            ],
            'category_ids' => [
                'present',
                'array',
            ],
            'category_ids.*' => [
                'integer',
                Rule::exists('customer_categories', 'id')->where(function ($query) {
                    return $query
                        ->where('user_id', auth()->id())
                        ->whereIn('id', $this->category_ids);
                }),
            ]
        ];
    }

    public function messages(): array
    {
        $messages = [];

        foreach ($this->category_ids as $index => $categoryId) {
            $messages[sprintf('category_ids.%d.exists', $index)] = sprintf('The selected category id (%d) is invalid.', $categoryId);
        }

        return $messages;
    }

    public function bodyParameters(): array
    {
        return [
            'customer_id' => [
                'example' => 1,
            ],
            'category_ids' => [
                'example' => [1, 2, 3],
            ],
            'category_ids.*' => [
                'example' => [1, 2, 3],
            ],
        ];
    }
}
