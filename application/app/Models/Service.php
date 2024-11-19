<?php

namespace App\Models;

use App\Services\WebhookService;
use App\Enums\WebhookEventTargetName;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ScopedRouteModelBindingTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @method static create(mixed $validated)
 */
class Service extends Model
{
    use HasFactory;
    use ScopedRouteModelBindingTrait;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'unit_price',
        'supplier',
    ];

    public static function boot(): void
    {
        parent::boot();
        self::created(function ($model) {
            WebhookService::handle($model, WebhookEventTargetName::SERVICE_CREATED);
        });
        self::updated(function ($model) {
            WebhookService::handle($model, WebhookEventTargetName::SERVICE_UPDATED);
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(ServiceCategory::class, 'service_category_associations');
    }
}
