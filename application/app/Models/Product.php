<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @method static create(mixed $validated)
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'sku',
        'description',
        'unit_type',
        'unit_price',
        'supplier',
    ];

    /**
     * This ensures the route model binding always loads the record owned by the logged-in user
     *
     * @param $value
     * @param $field
     * @return Model|Builder
     */
    public function resolveRouteBinding($value, $field = null): Model|Builder
    {
        return $this
            ->query()
            ->where('id', $value)
            ->where('user_id', auth()->id())
            ->firstOrFail();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(ProductCategory::class, 'product_category_associations');
    }
}
