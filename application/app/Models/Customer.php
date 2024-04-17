<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
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

    protected $fillable = [
        'user_id',
        'name',
        'tax_number',
        'tax_rate',
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

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
