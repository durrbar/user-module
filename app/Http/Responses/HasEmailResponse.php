<?php

declare(strict_types=1);

namespace Modules\User\Http\Responses;

use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;
use Modules\User\Contracts\BaseResponse;
use Symfony\Component\HttpFoundation\Response;

class HasEmailResponse implements BaseResponse
{
    public function toResponse($request): Response
    {
        return $request->wantsJson()
            ? response()->json([
                'message' => 'Your email is already verified',
            ], 200)
            : redirect()->intended(Fortify::redirects('email-verification'));
    }
}
