<?php

declare(strict_types=1);

namespace Modules\User\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Attributes\Appends;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Fortify\Contracts\PasskeyUser;
use Laravel\Fortify\PasskeyAuthenticatable;
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

/**
 * @property string $id
 * @property string $email
 * @property string|null $password
 * @property string|null $avatar
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $phone
 * @property string|null $birthday
 * @property string|null $gender
 * @property string|null $locale
 * @property Carbon|null $email_verified_at
 */
#[UseFactory(UserFactory::class)]
#[Table('users')]
#[Appends([
    'avatar_url',
    'name',
    'email_verified',
])]
#[Fillable([
    'email',
    'avatar',
    'first_name',
    'last_name',
    'password',
    'phone',
    'birthday',
    'gender',
    'email_verified_at',
])]
#[Hidden([
    'password',
    'remember_token',
])]
class User extends Authenticatable implements HasLocalePreference, MustVerifyEmail, PasskeyUser
{
    use HasApiTokens;

    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use HasNotification;
    use HasProfileAvatar;
    use HasRoles;
    use HasUuids;
    use Notifiable;
    use PasskeyAuthenticatable;
    use TwoFactorAuthenticatable;

    public function getEmailVerifiedAttribute(): bool
    {
        return $this->hasVerifiedEmail();
    }

    /**
     * Return the full name of the customer.
     */
    public function getNameAttribute(): string
    {
        $fullName = preg_replace(
            '/\s+/',
            ' ',
            "{$this->first_name} {$this->last_name}"
        ) ?? '';

        return mb_trim(
            $fullName
        );
    }

    /**
     * @return HasMany<Address, $this>
     */
    public function address(): HasMany
    {
        return $this->hasMany(Address::class, 'customer_id');
    }

    /**
     * @return HasMany<Conversation, $this>
     */
    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class, 'user_id');
    }

    /**
     * @return HasMany<Order, $this>
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'customer_id')->with(['products.variation_options', 'reviews']);
    }

    /**
     * @return HasOne<Profile, $this>
     */
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class, 'customer_id');
    }

    /**
     * @return HasOne<Wallet, $this>
     */
    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class, 'customer_id');
    }

    /**
     * @return HasMany<Shop, $this>
     */
    public function shops(): HasMany
    {
        return $this->hasMany(Shop::class, 'owner_id');
    }

    /**
     * @return HasMany<Shop, $this>
     */
    public function refunds(): HasMany
    {
        return $this->hasMany(Shop::class, 'customer_id');
    }

    /**
     * @return BelongsTo<Shop, $this>
     */
    public function managed_shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    /**
     * @return HasMany<Provider, $this>
     */
    public function providers(): HasMany
    {
        return $this->hasMany(Provider::class, 'user_id', 'id');
    }

    /**
     * @return HasMany<Review, $this>
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'user_id');
    }

    /**
     * @return HasMany<Question, $this>
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'user_id');
    }

    /**
     * @return HasMany<OrderedFile, $this>
     */
    public function ordered_files(): HasMany
    {
        return $this->hasMany(OrderedFile::class, 'customer_id');
    }

    /**
     * Follow shop
     *
     * @return BelongsToMany<Shop, $this, Pivot, 'pivot'>
     */
    public function follow_shops(): BelongsToMany
    {
        return $this->belongsToMany(Shop::class, 'user_shop');
    }

    /**
     * Follow shop
     *
     * @return HasMany<PaymentGateway, $this>
     */
    public function payment_gateways(): HasMany
    {
        return $this->HasMany(PaymentGateway::class, 'user_id');
    }

    /**
     * faqs
     *
     * @return HasMany<Faqs, $this>
     */
    public function faqs(): HasMany
    {
        return $this->HasMany(Faqs::class);
    }

    /**
     * terms and conditions
     *
     * @return HasMany<TermsAndConditions, $this>
     */
    public function terms_and_conditions(): HasMany
    {
        return $this->HasMany(TermsAndConditions::class);
    }

    /**
     * coupons
     *
     * @return HasMany<Coupon, $this>
     */
    public function coupon(): HasMany
    {
        return $this->HasMany(Coupon::class);
    }

    public function loadLastOrder(): self
    {
        $data = $this->orders()->whereNull('parent_id')
            ->where('order_status', OrderStatus::Completed->value)
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

    /**
     * @return HasMany<SocialAccount, $this>
     */
    public function socialAccounts(): HasMany
    {
        return $this->hasMany(SocialAccount::class);
    }

    /**
     * @return HasMany<SocialAccount, $this>
     */
    public function socialLinks(): HasMany
    {
        return $this->socialAccounts()->whereNotNull('profile_url');
    }

    /**
     * Get all addresses created by the user.
     *
     * @return HasMany<Address, $this>
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class, 'created_by');
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
