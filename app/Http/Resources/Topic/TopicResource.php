<?php

namespace App\Http\Resources\Topic;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TopicResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * 
     * @param \Iluminate\Http\Request $request
     * @return array
     */
    public function toArray(Request $request)
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'service'           => $this->service,
            'pct_lower'         => $this->pct_lower,
            'pct_upper'         => $this->pct_upper,
            'physical_state'    => $this->physical_state,
            'is_active'         => $this->is_active ?? true,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
            'deleted_at'        => $this->deleted_at
        ];
    }
}