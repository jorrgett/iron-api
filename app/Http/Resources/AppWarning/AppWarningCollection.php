<?php

namespace App\Http\Resources\AppWarning;

use App\Http\Resources\ApiCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AppWarningCollection extends ApiCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data'  => AppWarningResource::collection($this->collection),
            'meta'  => $this->dataMeta($request),
            'links' => $this->dataLinks($request)
        ];
    }
}
