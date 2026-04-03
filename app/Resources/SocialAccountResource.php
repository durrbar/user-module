<?php

declare(strict_types=1);

namespace Modules\User\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SocialAccountResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $resource = $this->resource;
        $providerName = data_get($resource, 'provider_name');

        if (! is_string($providerName) || $providerName === '') {
            return [];
        }

        return [
            $providerName => data_get($resource, 'profile_url'),
        ];
    }
}
