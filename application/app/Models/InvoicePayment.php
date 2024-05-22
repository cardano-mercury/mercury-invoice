<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoicePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'payment_date',
        'payment_method',
        'payment_currency',
        'payment_amount',
        'payment_reference',
        'crypto_asset_name',
        'crypto_asset_ada_price',
        'crypto_asset_quantity',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'payment_date' => 'datetime:Y-m-d',
            'status' => Status::class,
        ];
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
