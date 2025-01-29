<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use App\Http\Resources\ApiCollection;
use App\Http\Resources\User\UserAuditResource;

class UserAuditCollection extends ApiCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data'  => UserAuditResource::collection($this->collection),
            'meta'  => $this->dataMeta($request),
            'links' => $this->dataLinks($request)
        ];
    }
}
