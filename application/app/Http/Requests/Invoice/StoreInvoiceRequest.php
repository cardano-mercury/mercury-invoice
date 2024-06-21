<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
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
            'PUT' => $this->invoice->user_id === auth()->id(),
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
            'customer_id' => ['required', Rule::exists('customers', 'id')->where(static function ($query) {
                return $query->where('user_id', auth()->id());
            })],
            'customer_email_ids.*' => ['required', 'min:1', Rule::exists('emails', 'id')->where(function ($query) {
                return $query->where('customer_id', $this->input('customer_id'));
            })],
            'billing_address_id' => ['nullable', Rule::exists('addresses', 'id')->where(function ($query) {
                return $query->where('customer_id', $this->input('customer_id'));
            })],
            'shipping_address_id' => ['nullable', Rule::exists('addresses', 'id')->where(function ($query) {
                return $query->where('customer_id', $this->input('customer_id'));
            })],
            'customer_reference' => ['nullable', 'string', 'max:64'],
            'issue_date' => ['required', 'date', 'date_format:Y-m-d'],
            'due_date' => ['required', 'date', 'date_format:Y-m-d'],
            'items.*.product_id' => ['nullable', Rule::exists('products', 'id')->where(static function ($query) {
                return $query->where('user_id', auth()->id());
            })],
            'items.*.service_id' => ['nullable', Rule::exists('services', 'id')->where(static function ($query) {
                return $query->where('user_id', auth()->id());
            })],
            'items.*.sku' => ['nullable', 'string', 'max:32'],
            'items.*.description' => ['required', 'min:3', 'max:1024'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.000001'],
            'items.*.unit_price' => ['required', 'numeric'],
            'items.*.tax_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'save_mode' => ['required', 'in:Draft,Publish'],
        ];
    }

    public function messages(): array
    {
        return [
            'customer_email_ids.*.exists' => 'The email #:position does not exist in our records.',

            'items.*.product_id.exists' => 'The item #:position product does not exist in our records.',
            'items.*.service_id.exists' => 'The item #:position service does not exist in our records.',

            'items.*.description.required' => 'The item #:position description is required.',
            'items.*.description.min' => 'The item #:position description must be at least :min characters.',
            'items.*.description.max' => 'The item #:position description cannot be greater than :max characters.',

            'items.*.quantity.required' => 'The item #:position quantity is required.',
            'items.*.quantity.numeric' => 'The item #:position quantity must be a number.',
            'items.*.quantity.min' => 'The item #:position quantity cannot be less than :min.',

            'items.*.unit_price.required' => 'The item #:position unit price is required.',
            'items.*.unit_price.numeric' => 'The item #:position unit price must be a number.',

            'items.*.tax_rate.required' => 'The item #:position tax rate is required.',
            'items.*.tax_rate.numeric' => 'The item #:position tax rate must be a number.',
            'items.*.tax_rate.min' => 'The item #:position tax rate cannot be less than :min.',
            'items.*.tax_rate.max' => 'The item #:position tax rate cannot be greater than :max.',
        ];
    }
}
