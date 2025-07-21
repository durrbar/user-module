<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $table = 'providers';

    protected $fillable = ['provider', 'provider_user_id', 'user_id'];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
