<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WebhookLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'webhook_id',
        'status',
        'event_name',
        'payload',
        'attempts',
    ];

    protected $appends = ['formatted_datetime'];

    public function webhook(): BelongsTo
    {
        return $this->belongsTo(Webhook::class);
    }

    public function getFormattedDateTimeAttribute(): array
    {
        return [
            'datetime' => $this->created_at->toDateTimeString(),
            'diff' => $this->created_at->diffForHumans(),
        ];
    }
}
