<?php

namespace App\Http\Resources\Service;

use App\Http\Resources\ApiCollection;
use Illuminate\Http\Request;

class ServiceCollection extends ApiCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data'  => ServiceResource::collection($this->collection),
            'meta'  => $this->dataMeta($request),
            'links' => $this->dataLinks($request)
        ];
    }
}
