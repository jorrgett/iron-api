<?php

namespace App\Http\Resources\UserNotification;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserByNotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * 
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'user_id'           => $this->user->res_partner_id,
            'name'              => $this->user->full_name,
            'email'             => $this->user->email,
            'phone'             => $this->user->country_code. $this->user->phone,
            'notification_id'   => $this->notification_id,
            'status'            => $this->status
        ];
    }
}