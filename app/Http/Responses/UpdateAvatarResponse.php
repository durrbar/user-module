<?php

declare(strict_types=1);

namespace Modules\User\Http\Responses;

use Illuminate\Http\Request;
use Modules\User\Contracts\BaseResponse;
use Symfony\Component\HttpFoundation\Response;

class UpdateAvatarResponse implements BaseResponse
{
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
