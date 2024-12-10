<?php

namespace App\Http\Resources\Role;

use Illuminate\Http\Request;
use App\Http\Resources\Role\RoleResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RoleCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request)
    {
        return RoleResource::collection($this->collection);
    }
}
