<?php

namespace App\Http\Resources\ServiceItem;

use Illuminate\Http\Request;
use App\Http\Resources\ApiCollection;
use App\Http\Resources\ServiceItem\ServiceItemResource;

class ServiceItemCollection extends ApiCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data'  => ServiceItemResource::collection($this->collection),
            'meta'  => $this->dataMeta($request),
            'links' => $this->dataLinks($request)
        ];
    }
}
