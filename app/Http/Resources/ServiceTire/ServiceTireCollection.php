<?php

namespace App\Http\Resources\ServiceTire;

use App\Http\Resources\ApiCollection;
use Illuminate\Http\Request;
use App\Http\Resources\ServiceTire\ServiceTireResource;

class ServiceTireCollection extends ApiCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data'  => ServiceTireResource::collection($this->collection),
            'meta'  => $this->dataMeta($request),
            'links' => $this->dataLinks($request)
        ];
    }
}
