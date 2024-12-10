<?php

namespace App\Http\Resources\UserNotification;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserNotificationResource extends JsonResource
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
            'user_id'           => $this->user_id,
            'name'              => $this->user->full_name,
            'email'             => $this->user->email,
            'phone'             => $this->user->country_code. $this->user->phone,
            'notification_id'   => $this->notification_id,
            'title'             => "VEHICULO {$this->vehicle->plate}: " . $this->notification->title,
            'body'              => "Hola {$this->user->full_name}, " . $this->notification->body,
            'vehicle_id'        => $this->vehicle_id,
            'vehicle_odoo_id'   => $this->vehicle->odoo_id,
            'vehicle_brand'     => $this->vehicle->vehicle_brands->name,
            'vehicle_model'     => $this->vehicle->vehicle_models->name,
            'vehicle_plate'     => $this->vehicle->plate,
            'tire_location'     => $this->tire_location,
            'status'            => $this->status,
            'sent_date'         => $this->sent_date,
            'read_date'         => $this->read_date,
            'topic_1'           => $this->topic_1,
            'topic_2'           => $this->topic_2,
            'created_at'        => $this->created_at->format('Y-m-d H:m:s'),
            'updated_at'        => $this->updated_at->format('Y-m-d H:m:s')
        ];
    }
}