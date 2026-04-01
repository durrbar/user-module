<?php

namespace Modules\User\Http\Responses;

use Illuminate\Http\Request;
use Modules\User\Contracts\BaseResponse;
use Symfony\Component\HttpFoundation\Response;

class UpdateAvatarResponse implements BaseResponse
{
    /**
     * Create an HTTP response that represents the object.
     *
     */
    public function toResponse($request): Response
    {
        /** @var Request $request */

        return $request->wantsJson()
            ? response()->json([
                'message' => 'Profile avatar updated',
            ], Response::HTTP_OK)
            : back()->with('status', 'profile avatar updated');
    }
}
