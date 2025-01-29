<?php

namespace App\Transformers;

use App\Models\ServiceAligment;

class AligmentServiceTransfomer extends Transformer
{
    /**
     * @param $store
     * @return array
     */
    public function schema($aligment): array
    {
        return [
            'service_id'            => $aligment['service_id'] ?? null,
            'eje'                   => $aligment['eje'] ?? null,
            'valor'                 => $aligment['valor'] ?? null,
            'full_convergence_d'    => $aligment['full_convergence_d'] ?? null,
            'semiconvergence_izq_d' => $aligment['semiconvergence_izq_d'] ?? null,
            'semiconvergence_der_d' => $aligment['semiconvergence_der_d'] ?? null,
            'camber_izq_d'          => $aligment['camber_izq_d'] ?? null,
            'camber_der_d'          => $aligment['camber_der_d'] ?? null,
            'odoo_id'               => $aligment['id'] ?? null,
            'sequence_id'           => (new ServiceAligment())->incrementSequence()
        ];
    }
}
