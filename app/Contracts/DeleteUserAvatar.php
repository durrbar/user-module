<?php

namespace Modules\User\Contracts;

use Modules\User\Models\User;

interface DeleteUserAvatar
{
    public function delete(User $user): void;
}
