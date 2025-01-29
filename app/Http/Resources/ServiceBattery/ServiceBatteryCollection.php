<?php

namespace App\Http\Resources\ServiceBattery;

use App\Http\Resources\ApiCollection;
use Illuminate\Http\Request;
use App\Http\Resources\ServiceBattery\ServiceBatteryResource;

class ServiceBatteryCollection extends ApiCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data'  => ServiceBatteryResource::collection($this->collection),
            'meta'  => $this->dataMeta($request),
            'links' => $this->dataLinks($request)
        ];
    }
}
