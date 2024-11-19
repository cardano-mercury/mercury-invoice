<?php

namespace App\Models;

use App\Services\WebhookService;
use App\Enums\WebhookEventTargetName;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ScopedRouteModelBindingTrait;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @method static create(array $validated)
 */
class Customer extends Model
{
    use HasFactory;
    use ScopedRouteModelBindingTrait;

    protected $fillable = [
        'user_id',
        'name',
        'tax_number',
        'tax_rate',
    ];

    public static function boot(): void
    {
        parent::boot();
        self::created(function ($model) {
            WebhookService::handle($model, WebhookEventTargetName::CUSTOMER_CREATED);
        });
        self::updated(function ($model) {
            WebhookService::handle($model, WebhookEventTargetName::CUSTOMER_UPDATED);
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(CustomerCategory::class, 'customer_category_associations');
    }

    public function defaultEmail(): HasOne
    {
        return $this->hasOne(Email::class)
            ->where('is_default', true);
    }

    public function emails(): HasMany
    {
        return $this->hasMany(Email::class);
    }

    public function phones(): HasMany
    {
        return $this->hasMany(Phone::class);
    }

    public function defaultPhone(): HasOne
    {
        return $this->hasOne(Phone::class)
            ->where('is_default', true);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function defaultAddress(): HasOne
    {
        return $this->hasOne(Address::class)
            ->where('is_default', true);
    }
}
