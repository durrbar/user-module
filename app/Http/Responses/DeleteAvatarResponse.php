<?php

namespace Modules\User\Http\Responses;

use Modules\User\Contracts\BaseResponse;

class DeleteAvatarResponse implements BaseResponse
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        return $request->wantsJson()
            ? response()->json([
                'message' => 'Profile avatar deleted',
            ], 200)
            : back()->with('status', 'profile avatar deleted');
    }
}
