<?php

namespace Modules\User\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'email'       => $this->email,
            'avatar'       => $this->avatar,
            'avatarUrl'   => $this->avatar_url,
            'firstName'   => $this->first_name,
            'lastName'    => $this->last_name,
            'phone'       => $this->phone,
            'birthday'    => $this->birthday,
            'gender'      => $this->gender,
            'locale'      => $this->locale,
            'has2FA'      => $this->two_factor_confirmed_at ? true : false,
            'createdAt'   => $this->created_at,
            'updatedAt'   => $this->updated_at,
            'socialLinks' => SocialAccountResource::collection($this->whenLoaded('socialLinks'))
        ];
    }
}
