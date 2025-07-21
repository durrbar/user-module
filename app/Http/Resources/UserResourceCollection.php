<?php

namespace Modules\User\Http\Resources;

use Illuminate\Http\Request;
use Modules\Core\Http\Resources\Resource;

class UserResourceCollection extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
