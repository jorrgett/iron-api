<?php

namespace App\Http\Resources\Notification;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'id'            => $this->id,
            'name'          => $this->name,
            'title'         => $this->title,
            'body'          => $this->body,
            'type'          => $this->type,
            'is_active'     => $this->is_active ?? true,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
            'deleted_at'    => $this->deleted_at
        ];
    }
}