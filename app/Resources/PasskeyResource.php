<?php

declare(strict_types=1);

namespace Modules\User\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PasskeyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'authenticator' => $this->authenticator,
            'createdAt' => $this->created_at->diffForHumans(),
            'lastUsedAt' => $this->last_used_at?->diffForHumans(),
        ];
    }
}
