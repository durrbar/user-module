<?php

declare(strict_types=1);

namespace Modules\User\Http\Responses;

use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;
use Modules\User\Contracts\BaseResponse;
use Symfony\Component\HttpFoundation\Response;

class SendEmailResponse implements BaseResponse
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  Request  $request
     * @return Response
     */
    public function toResponse($request)
    {
        return $request->wantsJson()
            ? response()->json([
                'message' => 'email verification sent',
            ], 200)
            : back()->with('status', Fortify::VERIFICATION_LINK_SENT);
    }
}
