<?php

declare(strict_types=1);

namespace Modules\User\Contracts;

use Modules\User\Models\User;

interface UpdatesUserAvatar
{
    /**
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void;
}
