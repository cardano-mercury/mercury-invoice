<?php

namespace App\Http\Requests\Webhook;

use App\Enums\HMACAlgorithm;
use Illuminate\Foundation\Http\FormRequest;

class StoreWebhookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return match($this->method()) {
            // Anyone can create new record
            'POST' => true,
            // Updating/Deleting must match record owner
            'PUT', 'DELETE' => $this->webhook->user_id === auth()->id(),
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
            'url' => ['required', 'url', 'min:3', 'max:2048'],
            'hmac_algorithm' => ['required', 'string', 'in:' . implode(',', HMACAlgorithm::values())],
            'max_attempts' => ['required', 'integer', 'min:1', 'max:60'],
            'timeout_seconds' => ['required', 'integer', 'min:1', 'max:60'],
            'retry_seconds' => ['required', 'integer', 'min:1', 'max:900'],
        ];
    }
}
