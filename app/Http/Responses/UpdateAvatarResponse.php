<?php

namespace Modules\User\Http\Responses;

use Modules\User\Contracts\BaseResponse;
use Symfony\Component\HttpFoundation\Response;

class UpdateAvatarResponse implements BaseResponse
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
                'message' => 'Profile avatar updated',
                'm2' => $request,
            ], Response::HTTP_OK)
            : back()->with('status', 'profile avatar updated');
    }
}
