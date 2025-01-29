<?php

namespace App\Http\Resources\Notification;

use App\Http\Resources\ApiCollection;
use Illuminate\Http\Request;

class NotificationCollection extends ApiCollection
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
            'data' => NotificationResource::collection($this->collection),
            'meta' => $this->dataMeta($request),
            'links' => $this->dataLinks($request)
        ];
    }
}