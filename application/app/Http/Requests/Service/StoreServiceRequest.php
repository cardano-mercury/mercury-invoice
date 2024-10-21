<?php

namespace App\Http\Requests\Service;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
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
            'PUT', 'PATCH' => $this->service->user_id === auth()->id(),
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
                Rule::unique('services', 'name')->where(function($query) {
                    return $query
                        ->where('user_id', auth()->id())
                        ->where('name', $this->name);
                })->ignore($this?->service?->id),
            ],
            'description' => [
                'nullable',
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
                'example' => 'Software Development',
            ],
            'description' => [
                'example' => 'Rate for software development',
            ],
            'unit_type' => [
                'example' => 'Hourly',
            ],
            'unit_price' => [
                'example' => 40.50,
            ],
            'supplier' => [
                'example' => 'Cardano Mercury Ltd',
            ],
        ];
    }
}
