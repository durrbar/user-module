<?php

namespace Modules\User\Traits;

use Illuminate\Support\Facades\Cache;
use Modules\Role\Enums\Permission;
use Modules\User\Models\User;

trait UsersTrait
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, User>
     */
    public function getAdminUsers(): \Illuminate\Database\Eloquent\Collection
    {
        return Cache::remember(
            'cached_admin',
            900,
            fn () => User::with('profile')->where('is_active', true)->permission(Permission::SuperAdmin->value)->get()
        );
    }
}
