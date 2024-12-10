<?php

namespace App\Http\Resources\Application;

use App\Http\Resources\ApiCollection;
use Illuminate\Http\Request;
use App\Http\Resources\Application\ApplicationResource;

class ApplicationCollection extends ApiCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data'  => ApplicationResource::collection($this->collection),
            'meta'  => $this->dataMeta($request),
            'links' => $this->dataLinks($request)
        ];
    }
}
