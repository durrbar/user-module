<?php

declare(strict_types=1);

namespace Modules\User\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SocialAccountResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            $this->provider_name => $this->profile_url,
        ];
    }
}
