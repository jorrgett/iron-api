<?php

namespace App\Transformers;

use App\Models\Vehicle;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class VehicleTransformer extends Transformer
{
    private $TYPE_VEHICLE = [
        'VEHICULO', 
        'CAMIONETA PEQUEÑA', 
        'CAMIONETA', 
        'AUTOBUS', 
        'CAMION PEQUEÑO', 
        'CAMION'
    ];

    private $ICON_MAP = [
        'VEHICULO' => 1,
        'CAMIONETA PEQUEÑA' => 2,
        'CAMIONETA' => 2,
        'AUTOBUS' => 3,
        'CAMION PEQUEÑO' => 4,
        'CAMION' => 4
    ];

    private $COLOR_MAP = [
        'white' => '#FFFFFF',
        'black' => '#000000',
        'gris' => '#9b9b9b',
        'plateado' => '#e3e4e5',
        'arena' => '#ece2c6',
        'amarillo' => '#FFFF00',
        'azul' => '#0000FF',
        'rojo' => '#FF0000',
        'verde' => '#00CC33',
        'naranja' => '#FF6600',
        'beige' => '#f5f5dc'
    ];

    /**
     * @param $vehicle
     * @return array
     */
    public function schema($vehicle): array
    {
        $vehicleData = $vehicle['vehicle'];

        return [
            'plate'                => $vehicleData->plate,
            'vehicle_brand_id'     => $vehicleData->vehicle_brand_id,
            'vehicle_model_id'     => $vehicleData->vehicle_model_id,
            'register_date'        => $vehicleData->register_date,
            'color'                => $vehicleData->color,
            'year'                 => $vehicleData->year,
            'transmission'         => $vehicleData->transmission,
            'fuel'                 => $vehicleData->fuel,
            'odometer'             => $vehicle['odometer']->value,
            'odoo_id'              => $vehicleData->id,
            'nickname'             => $this->setNickname($vehicleData->id, $vehicleData->plate),
            'color_hex'            => $this->setColor($vehicleData->color),
            'icon'                 => $this->setIcon($vehicleData->type_vehicle),
            'type_vehicle'         => $this->getTypeVehicle($vehicleData->type_vehicle),
            'odometer_unit'        => $vehicleData->odometer_unit,
            'sequence_id'          => (new Vehicle())->incrementSequence(),
            'uuid'                 => $this->setUuid($vehicleData->plate)
        ];
    }

    private function setIcon($type_vehicle)
    {
        return $this->ICON_MAP[$type_vehicle] ?? 1;
    }

    private function setNickname($vehicle_id, $plate)
    {
        $vehicle = Vehicle::where('odoo_id', $vehicle_id)->first();
        return $vehicle ? $vehicle->nickname : 'Auto ' . $plate;
    }

    private function setColor($color)
    {
        return $this->COLOR_MAP[$color] ?? 'No disponible';
    }

    private function getTypeVehicle($type_vehicle)
    {
        return in_array($type_vehicle, $this->TYPE_VEHICLE) ? $type_vehicle : 'VEHICULO';
    }

    private function setUuid($plate)
    {
        $vehicle = Vehicle::plate($plate)->first();

        if ($vehicle && !empty($vehicle->uuid)) {
            return $vehicle->uuid;
        }

        $uuid = Str::uuid()->toString();
        return $uuid;
        }
}