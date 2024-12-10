<?php

namespace App\Http\Resources\AppParameters;

use Illuminate\Http\Resources\Json\JsonResource;

class AppParametersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'key'           => $this->key,
            'value'         => $this->value,
            'type'          => $this->type,
            'description'   => $this->description,
            'is_active'     => $this->is_active,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
            'deleted_at'    => $this->deleted_at
        ];
    }
}
