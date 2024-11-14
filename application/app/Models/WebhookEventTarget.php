<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method static create(array $validated)
 */
class WebhookEventTarget extends Model
{
    use HasFactory;

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'webhook_id',
        'event_name',
    ];

    public function webhook(): BelongsTo
    {
        return $this->belongsTo(Webhook::class);
    }
}
