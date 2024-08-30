<?php

namespace App\Models;

use Throwable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Jetstream\HasProfilePhoto;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory;
    use Notifiable;
    use HasApiTokens;
    use HasProfilePhoto;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'account_currency',
        'business_name',
        'business_terms',
        'stripe_config',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
        'stripe_config',
        'crypto_config',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function customerCategories(): HasMany
    {
        return $this->hasMany(CustomerCategory::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function productCategories(): HasMany
    {
        return $this->hasMany(ProductCategory::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function servicesCategories(): HasMany
    {
        return $this->hasMany(ServiceCategory::class);
    }

    public function webhooks(): HasMany
    {
        return $this->hasMany(Webhook::class);
    }

    public function getStripeConfigAttribute(string|null $value): array|null
    {
        if ($value) {
            try {
                return json_decode($value, true, 512, JSON_THROW_ON_ERROR);
            } catch (Throwable) { }
        }

        return null;
    }

    public function getCryptoConfigAttribute(string|null $value): array|null
    {
        if ($value) {
            try {
                return json_decode($value, true, 512, JSON_THROW_ON_ERROR);
            } catch (Throwable) { }
        }

        return null;
    }
}
