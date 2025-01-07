<?php

namespace Modules\User\Actions\Profile;

use Modules\User\Models\User;
use Modules\User\Contracts\DeleteUserPhoto;

class DeleteProfilePhoto implements DeleteUserPhoto
{
    /**
     * delete the given user's photo.
     *
     * @param  array<string
     */
    public function delete(User $user): void
    {
        $user->deleteProfilePhoto();
    }
}
