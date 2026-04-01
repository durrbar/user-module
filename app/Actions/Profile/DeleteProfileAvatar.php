<?php

namespace Modules\User\Actions\Profile;

use Modules\User\Contracts\DeleteUserAvatar;
use Modules\User\Models\User;

class DeleteProfileAvatar implements DeleteUserAvatar
{
    /**
     * Delete the given user's avatar.
     */
    public function delete(User $user): void
    {
        $user->deleteProfileAvatar();
    }
}
