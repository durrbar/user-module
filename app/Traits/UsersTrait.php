<?php

declare(strict_types=1);

namespace Modules\User\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Modules\Role\Enums\Permission;
use Modules\User\Models\User;

trait UsersTrait
{
    /**
     * @return Collection<int, User>
     */
    public function getAdminUsers(): Collection
    {
        return Cache::remember(
            'cached_admin',
            900,
            fn () => User::with('profile')->where('is_active', true)->permission(Permission::SuperAdmin->value)->get()
        );
    }
}
