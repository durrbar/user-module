<?php

namespace Modules\User\Actions\Profile;

use Modules\User\Contracts\UpdatesUserAvatar;
use Modules\User\Models\User;

class UpdateProfileAvatar implements UpdatesUserAvatar
{
    /**
     * Update the given user's avatar.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        // Extract the uploaded file
        if (isset($input['photo']) && $input['photo'] instanceof \Illuminate\Http\UploadedFile) {
            $user->updateProfileAvatar($input['photo'], $user);
        } else {
            throw new \InvalidArgumentException('Invalid file provided.');
        }
    }
}
