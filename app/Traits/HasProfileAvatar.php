<?php

namespace Modules\User\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Common\Facades\FileHelper;
use Modules\User\Models\User;

trait HasProfileAvatar
{
    /**
     * Update the user's profile avatar.
     *
     * @return void
     */
    public function updateProfileAvatar(UploadedFile $avatar, User $user)
    {
        $previousAvatar = $user->avatar;

        $path = FileHelper::setFile($avatar)
            ->setPath('uploads/user/avatar')
            ->generateUniqueFileName()
            ->upload()->getPath();

        // Update user's avatar path
        $user->avatar = $path;
        $user->save();

        // Delete previous avatar if it exists
        if ($previousAvatar) {
            Storage::delete($previousAvatar);
        }
    }

    /**
     * Delete the user's profile avatar.
     *
     * @return void
     */
    public function deleteProfileAvatar()
    {
        if (is_null($this->avatar)) {
            return;
        }

        Storage::delete($this->avatar);
        $this->forceFill([
            'avatar' => null,
        ])->save();
    }

    /**
     * Get the URL to the user's profile avatar.
     *
     * @return string
     */
    public function getAvatarUrlAttribute()
    {
        return $this->avatar
            ? Storage::url($this->avatar)
            : '';
    }
}
