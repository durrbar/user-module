<?php

namespace Modules\User\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Address\Models\Address;
use Modules\Notification\Traits\HasNotification;
use Modules\User\Traits\HasProfileAvatar;
use Spatie\Permission\Traits\HasRoles;

// use Modules\User\Database\Factories\UserFactory;

class UserOld extends Authenticatable implements HasLocalePreference, MustVerifyEmail
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

    // protected static function newFactory(): UserFactory
    // {
    //     // return UserFactory::new();
    // }

    /**
     * Return the full name of the customer.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return trim(
            preg_replace(
                '/\s+/',
                ' ',
                "{$this->first_name} {$this->last_name}"
            )
        );
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
}
