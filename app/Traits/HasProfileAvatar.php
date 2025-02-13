<?php

namespace Modules\User\Traits;

use Modules\User\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HasProfileAvatar
{
    /**
     * Update the user's profile avatar.
     *
     * @param \Illuminate\Http\UploadedFile $avatar
     * @return void
     */
    public function updateProfileAvatar(UploadedFile $avatar, User $user)
    {
        $previousAvatar = $user->avatar;

        // Define custom name for avatar
        $extension = $avatar->extension();
        $originalName = pathinfo($avatar->hashName(), PATHINFO_FILENAME);
        $fileName = $originalName . '_' . time() . '_' . uniqid() . '.' . $extension;

        if (extension_loaded('imagick')) {
            // Resize and compress the avatar using Imagick
            $image = \Intervention\Image\Laravel\Facades\Image::make($avatar->getPathname())
                ->resize(null, 300, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->encode($extension, 75); // 75 is the quality percentage

            // Store the image
            $path = 'uploads/user/avatar/' . $fileName;
            Storage::put($path, (string) $image);
        } else {
            // Directly upload the avatar without resizing
            $path = $avatar->storeAs('uploads/user/avatar', $fileName);
        }

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
            : $this->defaultProfileAvatarUrl();
    }

    /**
     * Get the default profile avatar URL if no avatar has been uploaded.
     *
     * @return string
     */
    protected function defaultProfileAvatarUrl()
    {
        $name = trim(collect(explode(' ', $this->name))->map(function ($segment) {
            return mb_substr($segment, 0, 1);
        })->join(' '));

        return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&color=7F9CF5&background=EBF4FF';
    }
}
