<?php

declare(strict_types=1);

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Permission\Models\Permission as SpatiePermission;

#[Table('permissions')]
class Permission extends SpatiePermission
{
    use HasUuids;

    protected $primaryKey = 'uuid';
}
