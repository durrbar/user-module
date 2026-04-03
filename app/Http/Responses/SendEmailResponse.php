<?php

declare(strict_types=1);

namespace Modules\User\Http\Responses;

use Laravel\Fortify\Fortify;
use Modules\User\Contracts\BaseResponse;
use Symfony\Component\HttpFoundation\Response;

class SendEmailResponse implements BaseResponse
{
    public function toResponse($request): Response
    {
        return $request->wantsJson()
            ? response()->json([
                'message' => 'email verification sent',
            ], 200)
            : back()->with('status', Fortify::VERIFICATION_LINK_SENT);
    }
}
