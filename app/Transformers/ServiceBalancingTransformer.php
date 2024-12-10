<?php

namespace App\Transformers;

use App\Models\ServiceBalancing;

class ServiceBalancingTransformer extends Transformer
{
    /**
     * @param $store
     * @return array
     */
    public function schema($service_balancing): array
    {
        return [
            'odoo_id'          => $service_balancing['id'],
            'service_id'       => $service_balancing['service_id'],
            'location'         => $service_balancing['location'],
            'lead_used'        => $service_balancing['lead_used'],
            'type_lead'        => $service_balancing['type_lead'] ?? null,
            'balanced'         => $service_balancing['balanced'],
            'wheel_good_state' => $service_balancing['wheel_good_state'],
            'wheel_scratched'  => $service_balancing['wheel_scratched'],
            'wheel_cracked'    => $service_balancing['wheel_cracked'],
            'wheel_bent'       => $service_balancing['wheel_bent'],
            'sequence_id'      => (new ServiceBalancing())->incrementSequence()
        ];
    }
}
