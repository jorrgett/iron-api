<?php

namespace App\Http\Resources\OilHistory;

use Illuminate\Http\Request;
use App\Http\Resources\ApiCollection;
use App\Http\Resources\OilHistory\OilHistoryResource;

class OilHistoryCollection extends ApiCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data'  => OilHistoryResource::collection($this->collection),
            'meta'  => $this->dataMeta($request),
            'links' => $this->dataLinks($request)
        ];
    }
}
