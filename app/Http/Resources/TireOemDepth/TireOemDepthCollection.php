<?php

namespace App\Http\Resources\TireOemDepth;

use App\Http\Resources\ApiCollection;
use Illuminate\Http\Request;
use App\Http\Resources\TireOemDepth\TireOemDepthResource;

class TireOemDepthCollection extends ApiCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data'  => TireOemDepthResource::collection($this->collection),
            'meta'  => $this->dataMeta($request),
            'links' => $this->dataLinks($request)
        ];
    }
}
