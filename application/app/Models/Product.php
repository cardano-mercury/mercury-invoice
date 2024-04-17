<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ScopedRouteModelBindingTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @method static create(mixed $validated)
 */
class Product extends Model
{
    use HasFactory;
    use ScopedRouteModelBindingTrait;

    protected $fillable = [
        'user_id',
        'name',
        'sku',
        'description',
        'unit_type',
        'unit_price',
        'supplier',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(ProductCategory::class, 'product_category_associations');
    }
}
