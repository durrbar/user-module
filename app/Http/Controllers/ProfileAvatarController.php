<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\User\Actions\Profile\DeleteProfileAvatar;
use Modules\User\Actions\Profile\UpdateProfileAvatar;
use Modules\User\Http\Requests\ProfileAvatarRequest;
use Modules\User\Http\Responses\DeleteAvatarResponse;
use Modules\User\Http\Responses\UpdateAvatarResponse;

class ProfileAvatarController extends Controller
{
    /**
     * Update the user's profile avatar.
     */
    public function update(ProfileAvatarRequest $request, UpdateProfileAvatar $updater): UpdateAvatarResponse
    {
        $updater->update($request->user(), $request->validated());

        return new UpdateAvatarResponse();
    }

    /**
     * Delete the user's profile avatar.
     *
     * @param  \Modules\User\App\Actions\Profile\DeleteProfileAvatar  $updater
     * @return \Modules\User\App\Http\Responses\DeleteAvatarResponse
     */
    public function delete(Request $request, DeleteProfileAvatar $deleter): DeleteAvatarResponse
    {
        $deleter->delete($request->user());

        return new DeleteAvatarResponse(); // Directly instantiate the response
    }
}
