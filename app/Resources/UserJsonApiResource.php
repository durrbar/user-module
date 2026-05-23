<?php

declare(strict_types=1);

namespace Modules\User\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\JsonApi\JsonApiResource;

class UserJsonApiResource extends JsonApiResource
{
    /**
     * Get the resource's attributes.
     */
    public function toAttributes(Request $request): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => $this->avatar,
            'avatarUrl' => $this->avatar_url,
            'firstName' => $this->first_name,
            'lastName' => $this->last_name,
            'phone' => $this->phone,
            'birthday' => $this->birthday,
            'gender' => $this->gender,
            'locale' => $this->locale,
            'has2FA' => (bool) $this->two_factor_confirmed_at,
            'emailVerifiedAt' => $this->email_verified_at,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }

    /**
     * The resource's relationships.
     */
    public function toRelationships(Request $request): array { 
        return [
            'socialAccounts' => SocialAccountJsonApiResource::class,
        ];
    }

    /**
     * Get the resource's type.
     */
    public function toType(Request $request): string
    {
        return 'users';
    }
}
