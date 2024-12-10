<?php

namespace App\Http\Resources\Topic;

use App\Http\Resources\ApiCollection;
use Illuminate\Http\Request;

class TopicCollection extends ApiCollection
{
    /**
     * Transform the resource collection into an array.
     * 
     * @param \Iluminate\Http\Request $request
     * @return array
     */
    public function toArray(Request $request)
    {
        return [
            'data' => TopicResource::collection($this->collection),
            'meta' => $this->dataMeta($request),
            'links' => $this->dataLinks($request)
        ];
    }
}