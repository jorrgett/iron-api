<?php

namespace App\Http\Resources\Error;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ErrorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'sequence_id'   => $this->sequence_id,
            'user_id'       => $this->user_id,
            'date'          => ($this->date)->format('Y-m-d H:m:s'),
            'screen'        => $this->screen,
            'action'        => $this->action,
            'api'           => $this->api,
            'error_message' => $this->error_message
        ];
    }
}
