<?php

namespace App\Helpers;

use App\Models\AppParameters;
use Exception;

class ParametersHelper
{
    public function get_app_parameters($key)
    {
        $parameter = AppParameters::where('key', $key)->first();

        switch ($parameter->type) {
            case 'integer':
                return intval($parameter->value);
            case 'float':
                return floatval($parameter->value);
            case 'boolean':
                return boolval($parameter->value);
            case 'string':
                return strval($parameter->value);
            case 'date':
                return date('Y-m-d', strtotime($parameter->value));
            default:
                throw new Exception("Tipo desconocido: " . $parameter->type);
        }
    }
}