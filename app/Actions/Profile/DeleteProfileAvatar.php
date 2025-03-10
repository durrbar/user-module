<?php

namespace Modules\User\Actions\Profile;

use Modules\User\Models\User;
use Modules\User\Contracts\DeleteUserAvatar;

class DeleteProfileAvatar implements DeleteUserAvatar
{
    /**
     * delete the given user's avatar.
     *
     * @param  array<string
     */
    public function delete(User $user): void
    {
        $user->deleteProfileAvatar();
    }
}
