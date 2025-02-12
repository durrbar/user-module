<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// use Modules\User\Database\Factories\SocialAccountFactory;

class SocialAccount extends Model
{
    use HasFactory;
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'provider_name',
        'provider_id',
        'access_token',
        'profile_url'
    ];

    // protected static function newFactory(): SocialAccountFactory
    // {
    //     // return SocialAccountFactory::new();
    // }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
