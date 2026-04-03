<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Modules\User\Actions\Profile\DeleteProfileAvatar;
use Modules\User\Actions\Profile\UpdateProfileAvatar;
use Modules\User\Http\Requests\ProfileAvatarRequest;
use Modules\User\Http\Responses\DeleteAvatarResponse;
use Modules\User\Http\Responses\UpdateAvatarResponse;
use Modules\User\Models\User;

class ProfileAvatarController extends Controller
{
    public function update(ProfileAvatarRequest $request, UpdateProfileAvatar $updater): UpdateAvatarResponse
    {
        $updater->update($this->resolveUser($request), $request->validated());

        return new UpdateAvatarResponse();
    }

    public function delete(Request $request, DeleteProfileAvatar $deleter): DeleteAvatarResponse
    {
        $deleter->delete($this->resolveUser($request));

        return new DeleteAvatarResponse();
    }

    /**
     * @throws AuthorizationException
     */
    private function resolveUser(Request $request): User
    {
        $user = $request->user();

        if (! $user instanceof User) {
            throw new AuthorizationException('Unauthenticated.');
        }

        return $user;
    }
}
