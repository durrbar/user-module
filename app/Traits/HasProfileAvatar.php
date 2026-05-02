<?php

declare(strict_types=1);

namespace Modules\User\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Core\Facades\FileHelper;
use Modules\User\Models\User;

trait HasProfileAvatar
{
    public function updateProfileAvatar(UploadedFile $avatar, User $user): void
    {
        $previousAvatar = $user->avatar;

        $path = FileHelper::setFile($avatar)
            ->setPath('uploads/user/avatar')
            ->generateUniqueFileName()
            ->upload()->getPath();

        $user->avatar = $path;
        $user->save();

        if (is_string($previousAvatar) && $previousAvatar !== '') {
            Storage::delete($previousAvatar);
        }
    }

    public function deleteProfileAvatar(): void
    {
        if (! is_string($this->avatar) || $this->avatar === '') {
            return;
        }

        Storage::delete($this->avatar);
        $this->forceFill([
            'avatar' => null,
        ])->save();
    }

    public function getAvatarUrlAttribute(): string
    {
        return is_string($this->avatar) && $this->avatar !== ''
            ? Storage::url($this->avatar)
            : '';
    }
}
