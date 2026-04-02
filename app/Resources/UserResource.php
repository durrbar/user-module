<?php

declare(strict_types=1);

namespace Modules\User\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $resource = $this->resource;

        return [
            'id' => data_get($resource, 'id'),
            'name' => data_get($resource, 'name'),
            'email' => data_get($resource, 'email'),
            'avatar' => data_get($resource, 'avatar'),
            'avatarUrl' => data_get($resource, 'avatar_url'),
            'firstName' => data_get($resource, 'first_name'),
            'lastName' => data_get($resource, 'last_name'),
            'phone' => data_get($resource, 'phone'),
            'birthday' => data_get($resource, 'birthday'),
            'gender' => data_get($resource, 'gender'),
            'locale' => data_get($resource, 'locale'),
            'has2FA' => data_get($resource, 'two_factor_confirmed_at') ? true : false,
            'emailVerifiedAt' => data_get($resource, 'email_verified_at'),
            'createdAt' => data_get($resource, 'created_at'),
            'updatedAt' => data_get($resource, 'updated_at'),
            'socialLinks' => SocialAccountResource::collection($this->whenLoaded('socialLinks')),
        ];
    }
}
