<?php

namespace App\Http\Requests\Service;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SyncServiceCategoryRequest extends FormRequest
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
            'service_id' => [
                'required',
                'integer',
                Rule::exists('services', 'id')->where(function ($query) {
                    return $query
                        ->where('user_id', auth()->id())
                        ->where('id', $this->service_id);
                })
            ],
            'category_ids' => [
                'present',
                'array',
            ],
            'category_ids.*' => [
                'integer',
                Rule::exists('service_categories', 'id')->where(function ($query) {
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
            'service_id' => [
                'example' => 1,
            ],
        ];
    }
}
