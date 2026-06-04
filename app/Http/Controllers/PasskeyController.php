<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Fortify\Features;
use Modules\User\Resources\PasskeyResource;

class PasskeyController extends Controller
{
    public function passkeys(Request $request): JsonResponse
    {
        return response()->json([
            'passkeys' => PasskeyResource::collection(Features::canManagePasskeys()
                ? $request->user()
                    ->passkeys()
                    ->select(['id', 'name', 'credential', 'created_at', 'last_used_at'])
                    ->latest()
                    ->get()
                    ->values()
                    ->all()
                : []),
        ], Response::HTTP_OK);
    }
}
