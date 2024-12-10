<?php

namespace App\Http\Resources\ServiceBalancing;

use App\Http\Resources\ApiCollection;
use Illuminate\Http\Request;
use App\Http\Resources\ServiceBalancing\ServiceBalancingResource;

class ServiceBalancingCollection extends ApiCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data'  => ServiceBalancingResource::collection($this->collection),
            'meta'  => $this->dataMeta($request),
            'links' => $this->dataLinks($request)
        ];
    }
}
