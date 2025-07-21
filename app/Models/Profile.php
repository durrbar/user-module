<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    protected $table = 'user_profiles';

    public $guarded = [];

    protected $casts = [
        'socials' => 'json',
        'avatar' => 'json',
        'notifications' => 'json',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
