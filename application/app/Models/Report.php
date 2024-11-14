<?php

namespace App\Models;

use App\Enums\ReportType;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ScopedRouteModelBindingTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use ScopedRouteModelBindingTrait;

    protected $fillable = [
        'user_id',
        'product_id',
        'service_id',
        'customer_id',
        'name',
        'type',
        'from_date',
        'to_date',
        'generated_at',
        'file_name',
        'status',
        'last_error',
    ];

    protected $casts = [
        'type' => ReportType::class,
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
