<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasUuids;
    
    /**
     * @var string[]
     */
    protected $fillable = [
        'email', 'token',
    ];
}
