<?php

namespace App\Transformers;

use App\Models\ServiceOperator;

class OperatorServiceTransformer extends Transformer
{
    /**
     * @param $store
     * @return array
     */
    public function schema($operator): array
    {
        return [
            'name'        => $operator['x_operador_serv_name'],
            'vat'         => $operator['x_operador_serv_vat'],
            'odoo_id'     => $operator['x_operador_serv_id'],
            'sequence_id' => (new ServiceOperator())->incrementSequence()
        ];
    }
}
