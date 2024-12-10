<?php

namespace App\Http\Resources\User;

use App\Http\Resources\ApiCollection;
use App\Http\Resources\User\UserResourceDetail;

class UserCollection extends ApiCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data'  => UserResourceDetail::collection($this->collection),
            'meta'  => $this->dataMeta($request),
            'links' => $this->dataLinks($request)
        ];
    }
}
