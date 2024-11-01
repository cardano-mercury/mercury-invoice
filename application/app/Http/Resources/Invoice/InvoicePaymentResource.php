<?php

namespace App\Http\Resources\Invoice;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoicePaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'payment_date' => $this->payment_date->format('d-m-Y'),
            'payment_method' => $this->payment_method,
            'payment_currency' => $this->payment_currency,
            'payment_amount' => (float) $this->payment_amount,
            'payment_reference' => $this->payment_reference,
            'crypto_asset_name' => $this->crypto_asset_name,
            'crypto_asset_ada_price' => $this->crypto_asset_ada_price,
            'crypto_asset_quantity' => (float) $this->crypto_asset_quantity,
            'crypto_wallet_name' => $this->crypto_wallet_name,
            'crypto_payment_ttl' => $this->crypto_payment_ttl,
            'crypto_payment_recipient_address' => $this->crypto_payment_recipient_address,
            'crypto_payment_last_checked' => ($this->crypto_payment_last_checked ? Carbon::createFromTimestamp($this->crypto_payment_last_checked)->toDateTimeString() : null),
            'crypto_payment_process_attempts' => $this->crypto_payment_process_attempts,
            'crypto_payment_last_error' => $this->crypto_payment_last_error,
            'status' => $this->status,
        ];
    }
}
