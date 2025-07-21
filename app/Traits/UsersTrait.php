<?php

namespace Modules\User\Traits;

use Illuminate\Support\Facades\Cache;
use Modules\Role\Enums\Permission;
use Modules\User\Models\User;

trait UsersTrait
{
    public function getAdminUsers()
    {
        return Cache::remember(
            'cached_admin',
            900,
            fn () => User::with('profile')->where('is_active', true)->permission(Permission::SUPER_ADMIN)->get()
        );
    }
}
