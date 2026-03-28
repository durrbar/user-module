<?php

declare(strict_types=1);

namespace Modules\User\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Address\Models\Address;
use Modules\Chat\Models\Conversation;
use Modules\Coupon\Models\Coupon;
use Modules\Ecommerce\Models\Faqs;
use Modules\Ecommerce\Models\Question;
use Modules\Ecommerce\Models\TermsAndConditions;
use Modules\Ecommerce\Models\Wallet;
use Modules\Notification\Traits\HasNotification;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderedFile;
use Modules\Payment\Models\PaymentGateway;
use Modules\Review\Models\Review;
use Modules\User\Traits\HasProfileAvatar;
use Modules\Vendor\Models\Shop;
use Spatie\Permission\Traits\HasRoles;

// use Modules\User\Database\Factories\UserFactory;

final class User extends Authenticatable implements HasLocalePreference, MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasNotification;
    use HasProfileAvatar;
    use HasRoles;
    use HasUuids;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The table associated with the model.
     */
    protected $table = 'users';

    protected $appends = [
        'avatar_url',
        'name',
        'email_verified',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'avatar',
        'first_name',
        'last_name',
        'password',
        'phone',
        'birthday',
        'gender',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getEmailVerifiedAttribute(): bool
    {
        return $this->hasVerifiedEmail();
    }

    /**
     * Return the full name of the customer.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return mb_trim(
            preg_replace(
                '/\s+/',
                ' ',
                "{$this->first_name} {$this->last_name}"
            )
        );
    }

    public function address(): HasMany
    {
        return $this->hasMany(Address::class, 'customer_id');
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class, 'user_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'customer_id')->with(['products.variation_options', 'reviews']);
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class, 'customer_id');
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class, 'customer_id');
    }

    public function shops(): HasMany
    {
        return $this->hasMany(Shop::class, 'owner_id');
    }

    public function refunds(): HasMany
    {
        return $this->hasMany(Shop::class, 'customer_id');
    }

    public function managed_shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function providers(): HasMany
    {
        return $this->hasMany(Provider::class, 'user_id', 'id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'user_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'user_id');
    }

    public function ordered_files(): HasMany
    {
        return $this->hasMany(OrderedFile::class, 'customer_id');
    }

    /**
     * Follow shop
     */
    public function follow_shops(): BelongsToMany
    {
        return $this->belongsToMany(Shop::class, 'user_shop');
    }

    /**
     * Follow shop
     */
    public function payment_gateways(): HasMany
    {
        return $this->HasMany(PaymentGateway::class, 'user_id');
    }

    /**
     * faqs
     */
    public function faqs(): HasMany
    {
        return $this->HasMany(Faqs::class);
    }

    /**
     * terms and conditions
     */
    public function terms_and_conditions(): HasMany
    {
        return $this->HasMany(TermsAndConditions::class);
    }

    /**
     * coupons
     */
    public function coupon(): HasMany
    {
        return $this->HasMany(Coupon::class);
    }

    public function loadLastOrder()
    {
        $data = $this->orders()->whereNull('parent_id')
            ->where('order_status', OrderStatus::COMPLETED)
            ->latest()->first();
        $this->setRelation('last_order', $data);

        return $this;
    }

    /**
     * Get the user's preferred locale.
     */
    public function preferredLocale(): string
    {
        return $this->locale ?? app()->getLocale();
    }

    public function socialAccounts(): HasMany
    {
        return $this->hasMany(SocialAccount::class);
    }

    public function socialLinks(): HasMany
    {
        return $this->socialAccounts()->whereNotNull('profile_url');
    }

    /**
     * Get all addresses created by the user.
     */
    public function addresses()
    {
        return $this->hasMany(Address::class, 'created_by');
    }

    // protected static function newFactory(): UserFactory
    // {
    //     // return UserFactory::new();
    // }

    protected static function boot()
    {
        parent::boot();
        // Order by updated_at desc
        self::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('updated_at', 'desc');
        });
    }

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
}
