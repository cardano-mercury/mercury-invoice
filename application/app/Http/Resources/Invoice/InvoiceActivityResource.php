<?php

namespace App\Http\Resources\Invoice;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'activity' => $this->activity,
            'when' => $this->formatted_datetime['datetime'],
            'diff' => $this->formatted_datetime['diff'],
        ];
    }
}
