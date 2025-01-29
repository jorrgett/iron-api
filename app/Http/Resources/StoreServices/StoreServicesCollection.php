<?php

namespace App\Http\Resources\StoreServices;

use App\Http\Resources\ApiCollection;
use Illuminate\Http\Request;

class StoreServicesCollection extends ApiCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data'  => StoreServicesResource::collection($this->collection),
            'meta'  => $this->dataMeta($request),
            'links' => $this->dataLinks($request)
        ];
    }
}
