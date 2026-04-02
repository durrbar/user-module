<?php

declare(strict_types=1);

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// use Modules\User\Database\Factories\SocialAccountFactory;

#[Fillable([
    'user_id',
    'provider_name',
    'provider_id',
    'access_token',
    'profile_url',
])]
class SocialAccount extends Model
{
    use HasUuids;

    // protected static function newFactory(): SocialAccountFactory
    // {
    //     // return SocialAccountFactory::new();
    // }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
