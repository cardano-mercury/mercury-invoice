<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method static create(array $data)
 */
class InvoiceActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'activity',
    ];

    protected $appends = ['formatted_datetime'];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function getFormattedDateTimeAttribute(): array
    {
        return [
            'datetime' => $this->created_at->toDateTimeString(),
            'diff' => $this->created_at->diffForHumans(),
        ];
    }
}
