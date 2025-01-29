<?php

namespace App\Http\Resources\AppParameters;

use App\Http\Resources\ApiCollection;


class AppParametersCollection extends ApiCollection
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
            'data' => AppParametersResource::collection($this->collection),
            'meta'  => $this->dataMeta($request),
            'links' => $this->dataLinks($request)
        ];
    }
}
