<?php

namespace App\Models;

use App\Enums\PhoneType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method static create(array $validated)
 */
class Phone extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'type',
        'name',
        'number',
        'is_default',
    ];

    protected $casts = [
        'type' => PhoneType::class,
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
