<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\User\Resources\UserResource;

final class UserController extends Controller
{
    /**
     * Get authenticated user data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        return response()->json([
            'user' => new UserResource($request->user()),
        ], Response::HTTP_OK);
    }
}
