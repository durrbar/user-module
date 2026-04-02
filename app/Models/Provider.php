<?php

declare(strict_types=1);

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

#[Table('providers')]
#[Fillable(['provider', 'provider_user_id', 'user_id'])]
#[Hidden([
    'created_at',
    'updated_at',
])]
class Provider extends Model
{
    use HasUuids;
}
