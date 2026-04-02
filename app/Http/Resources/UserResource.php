<?php

declare(strict_types=1);

namespace Modules\User\Http\Resources;

use Illuminate\Http\Request;
use Modules\Core\Http\Resources\Resource;

class UserResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $resource = $this->resource;

        return [
            'id' => data_get($resource, 'id'),
            'name' => data_get($resource, 'name'),
            'email' => data_get($resource, 'email'),
            'email_verified_at' => data_get($resource, 'email_verified_at'),
            'is_active' => data_get($resource, 'is_active'),
            'shop_id' => data_get($resource, 'shop_id'),
            'created_at' => data_get($resource, 'created_at'),
            'updated_at' => data_get($resource, 'updated_at'),
        ];
    }
}
