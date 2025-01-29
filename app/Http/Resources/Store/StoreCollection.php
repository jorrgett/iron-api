<?php

namespace App\Http\Resources\Store;

use App\Http\Resources\ApiCollection;
use Illuminate\Http\Request;
use App\Http\Resources\Store\StoreResource;

class StoreCollection extends ApiCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data'  => StoreResource::collection($this->collection),
            'meta'  => $this->dataMeta($request),
            'links' => $this->dataLinks($request)
        ];
    }
}
