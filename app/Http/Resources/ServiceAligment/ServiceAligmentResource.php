<?php

namespace App\Http\Resources\ServiceAligment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceAligmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            'odoo_id'               => $this->odoo_id,
            'service_id'            => $this->service_id,
            'eje'                   => $this->eje,
            'valor'                 => $this->valor,
            'full_convergence_d'    => $this->full_convergence_d,
            'semiconvergence_izq_d' => $this->semiconvergence_izq_d,
            'semiconvergence_der_d' => $this->semiconvergence_der_d,
            'camber_izq_d'          => $this->camber_izq_d,
            'camber_der_d'          => $this->camber_der_d,
            'sequence_id'           => $this->sequence_id,
        ];
    }
}
