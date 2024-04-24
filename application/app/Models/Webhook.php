<?php

namespace App\Models;

use App\Enums\HMACAlgorithm;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ScopedRouteModelBindingTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method static create(array $validated)
 */
class Webhook extends Model
{
    use HasFactory;
    use ScopedRouteModelBindingTrait;

    protected $fillable = [
        'user_id',
        'url',
        'hmac_secret',
        'hmac_algorithm',
        'max_attempts',
        'timeout_seconds',
        'retry_seconds',
    ];

    protected $casts = [
        'hmac_algorithm' => HMACAlgorithm::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function eventTargets(): HasMany
    {
        return $this->hasMany(WebhookEventTarget::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(WebhookLog::class);
    }
}
