<?php

namespace App\Models;

use App\Enums\Status;
use App\Traits\HashIdTrait;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ScopedRouteModelBindingTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @method static create(array $invoiceData)
 */
class Invoice extends Model
{
    use HasFactory;
    use HashIdTrait;
    use ScopedRouteModelBindingTrait;

    protected $fillable = [
        'user_id',
        'customer_id',
        'billing_address_id',
        'shipping_address_id',
        'currency',
        'customer_reference',
        'issue_date',
        'due_date',
        'last_notified',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'datetime:Y-m-d',
            'due_date' => 'datetime:Y-m-d',
            'last_notified' => 'datetime',
            'status' => Status::class,
        ];
    }

    protected $appends = ['invoice_reference', 'total', 'is_overdue'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function billingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function recipients(): BelongsToMany
    {
        return $this->belongsToMany(Email::class, 'invoice_recipients');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(InvoicePayment::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(InvoiceActivity::class);
    }

    public function getInvoiceReferenceAttribute(): string
    {
        return $this->encodeId($this->id);
    }

    public function getTotalAttribute(): float
    {
        $total = 0;
        if (!isset($this->items)) {
            $this->load('items');
        }
        foreach ($this->items as $item) {
            $subtotal = $item->quantity * $item->unit_price;
            $tax = $subtotal * ($item->tax_rate / 100);
            $total += ($subtotal + $tax);
        }
        return $total;
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->status === Status::PUBLISHED &&
            $this->due_date->lte(now());
    }
}
