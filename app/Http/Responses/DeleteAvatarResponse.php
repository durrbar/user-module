<?php

declare(strict_types=1);

namespace Modules\User\Http\Responses;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\User\Contracts\BaseResponse;

class DeleteAvatarResponse implements BaseResponse
{
    public function toResponse($request): \Symfony\Component\HttpFoundation\Response
    {
        /** @var Request $request */

        return $request->wantsJson()
            ? response()->json([
                'message' => 'Profile avatar deleted',
            ], Response::HTTP_OK)
            : back()->with('status', 'profile avatar deleted');
    }
}
