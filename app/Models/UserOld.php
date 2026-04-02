<?php

declare(strict_types=1);

namespace Modules\User\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Attributes\Appends;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Address\Models\Address;
use Modules\Notification\Traits\HasNotification;
use Modules\User\Traits\HasProfileAvatar;
use Spatie\Permission\Traits\HasRoles;

// use Modules\User\Database\Factories\UserFactory;

/**
 * @property string $id
 * @property string $email
 * @property string|null $avatar
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $phone
 * @property string|null $birthday
 * @property string|null $gender
 * @property string|null $locale
 * @property Carbon|null $email_verified_at
 */
#[Table('users')]
#[Appends([
    'avatar_url',
    'name',
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
class UserOld extends Authenticatable implements HasLocalePreference, MustVerifyEmail
{
    use HasApiTokens;
    use HasNotification;
    use HasProfileAvatar;
    use HasRoles;
    use HasUuids;
    use Notifiable;
    use TwoFactorAuthenticatable;

    // protected static function newFactory(): UserFactory
    // {
    //     // return UserFactory::new();
    // }

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
