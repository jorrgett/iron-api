<?php

namespace App\Odoo;

use App\Helpers\OdooHelper;
use App\Jobs\SyncProcessed;
use App\Models\ServiceSynced;
use App\Jobs\SyncPostProcessed;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\MaintenanceSchedule;
use Illuminate\Support\Facades\Log;

class OdooSync
{
    protected $serviceFromOdoo;
    protected $service;
    protected $not_processed;
    public $from;

    private $transformers = [
        'store'                    => \App\Transformers\StoreTransformer::class,
        'vehicle_brand'            => \App\Transformers\VehicleBrandTransformer::class,
        'vehicle_model'            => \App\Transformers\VehicleModelTransformer::class,
        'vehicle'                  => \App\Transformers\VehicleTransformer::class,
        'odometer'                 => \App\Transformers\OdometerTransformer::class,
        'service'                  => \App\Transformers\ServiceTransformer::class,
        'tire_brand'               => \App\Transformers\TireBrandTransformer::class,
        'tire_model'               => \App\Transformers\TireModelTransformer::class,
        'tire_size'                => \App\Transformers\TireSizeTransformer::class,
        'services_tires'           => \App\Transformers\ServiceTiresTransformer::class,
        'product_category'         => \App\Transformers\ProductCategoryTransformer::class,
        'product'                  => \App\Transformers\ProductTransformer::class,
        'operator'                 => \App\Transformers\OperatorServiceTransformer::class,
        'items_services'           => \App\Transformers\ServiceItemsTransformer::class,
        'alignment'                => \App\Transformers\AligmentServiceTransfomer::class,
        'services_oil'             => \App\Transformers\ServiceOilTransformer::class,
        'service_battery'          => \App\Transformers\ServiceBatteryTransformer::class,
        'service_balancing'        => \App\Transformers\ServiceBalancingTransformer::class,
        'battery_tire_brand'       => \App\Transformers\TireBrandTransformer::class,
        'battery_tire_model'       => \App\Transformers\TireModelTransformer::class,
        'service_oil_brand'        => \App\Transformers\TireBrandTransformer::class,
        'filter_brand'             => \App\Transformers\FilterBrandTransformer::class,
        'inspection_fluid'         => \App\Transformers\InspectionFluidTransformer::class,
    ];

    private $models = [
        'store'                    => \App\Models\Store::class,
        'vehicle_brand'            => \App\Models\VehicleBrand::class,
        'vehicle_model'            => \App\Models\VehicleModel::class,
        'vehicle'                  => \App\Models\Vehicle::class,
        'odometer'                 => \App\Models\Odometer::class,
        'service'                  => \App\Models\Service::class,
        'tire_brand'               => \App\Models\TireBrand::class,
        'tire_model'               => \App\Models\TireModel::class,
        'tire_size'                => \App\Models\TireSize::class,
        'services_tires'           => \App\Models\ServiceTire::class,
        'product_category'         => \App\Models\ProductCategory::class,
        'product'                  => \App\Models\Product::class,
        'operator'                 => \App\Models\ServiceOperator::class,
        'items_services'           => \App\Models\ServiceItem::class,
        'alignment'                => \App\Models\ServiceAligment::class,
        'services_oil'             => \App\Models\ServiceOil::class,
        'service_battery'          => \App\Models\ServiceBattery::class,
        'service_balancing'        => \App\Models\ServiceBalancing::class,
        'battery_tire_brand'       => \App\Models\TireBrand::class,
        'battery_tire_model'       => \App\Models\TireModel::class,
        'service_oil_brand'        => \App\Models\TireBrand::class,
        'filter_brand'             => \App\Models\TireBrand::class,
        'inspection_fluid'         => \App\Models\InspectionFluid::class,

    ];

    private const DEPENDENT_SERVICES = [
        'services_tires', 'items_services', 'tire_brand', 'tire_model',
        'tire_size', 'product', 'product_category', 'operator',
        'alignment', 'services_oil', 'service_battery', 'service_balancing',
        'battery_tire_brand', 'battery_tire_model', 'service_oil_brand', 'filter_brand'
    ];

    public function run(string $service, array $response, bool $not_processed, bool $procesado_iron, string $from): void
    {
        $this->service = $service;
        $this->not_processed = $not_processed;
        $this->serviceFromOdoo = new OdooHelper($this->from);
        
        foreach ($response as $data) {
            if ($this->isDependentService()) {
                $this->processDependentServices($data);
            } else {
                $this->processServiceItem($data);
            }

            $this->saveServiceSynced($data, $procesado_iron, $from);

            if ($from === 'gwmve' && $data->type_maintenance) {
                $this->processMaintenanceSchedule($data->type_maintenance);
            }
        }

        Log::info("Sync completed for service: {$this->service}");
    }

    private function isDependentService(): bool
    {
        return in_array($this->service, self::DEPENDENT_SERVICES);
    }

    private function processMaintenanceSchedule($typeMaintenance): void
    {
        $lastItem = MaintenanceSchedule::where('vehicle_id', $typeMaintenance->vehicle_id)
            ->where('status', 'scheduled')
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastItem && $lastItem->order < ($typeMaintenance->order + 1)) {
            $lastItem->done_date = now();
            $lastItem->status = 'done';
            $lastItem->save();
        }

        MaintenanceSchedule::upsert([
            'vehicle_id'           => $typeMaintenance->vehicle_id,
            'order'                => $typeMaintenance->order + 1,
            'maintenance_kms'      => $typeMaintenance->kilometer_maint,
            'maintenance_interval' => $typeMaintenance->windows_maint,
            'status'               => 'scheduled',
        ], ['vehicle_id', 'order']);
    }

    private function processServiceItem($records): void
    {
        $serviceItems = (new $this->transformers[$this->service])->transform($records);

        if (!$serviceItems) {
            return;
        }

        if ($this->service === 'items_services') {
            $this->models[$this->service]::upsert($serviceItems, ['odoo_id', 'service_id']);
        } else {
            $this->handleSpecialCases($serviceItems);
            $this->models[$this->service]::upsert($serviceItems, 'odoo_id');
        }
    }

    private function handleSpecialCases($serviceItems): void
    {
        if ($this->service === 'vehicle') {
            $this->processCustomVehicle($serviceItems);
        } elseif ($this->service === 'service') {
            $this->processServiceContact($serviceItems);
        }
    }

    private function processCustomVehicle($serviceItems): void
    {   
        $baseVehicle = $this->models['vehicle']::where('plate', $serviceItems['plate'])
        ->where('odoo_id', '!=', null)->first();

        $customVehicle = $this->models['vehicle']::where('plate', $serviceItems['plate'])
            ->where('odoo_id', null)->first();

        if ($baseVehicle && $customVehicle){
            $customVehicle->delete();
            $customVehicle = False;
        }
        
        if ($customVehicle) {
            Log::warning("Custom vehicle found: plate {$customVehicle['plate']}");
            $customVehicle->update(['odoo_id' => $serviceItems['odoo_id']]);
            Log::warning("odoo_id {$serviceItems['odoo_id']} set for vehicle {$customVehicle['plate']}");
        }
    }

    private function processServiceContact($serviceItems): void
    {
        $contact = \App\Models\Contacts::where('odoo_id', $serviceItems['owner_id'])
            ->orWhere('odoo_id', $serviceItems['driver_id'])->first();

        if ($contact) {
            $this->syncUserWithContact($contact, $serviceItems);
        }
    }

    private function syncUserWithContact($contact, $serviceItems): void
    {
        $user = \App\Models\User::where('phone', $contact['phone'])
            ->where('res_partner_id', null)->first();

        if ($user) {
            Log::warning("User match found: {$user['id']} for contact {$contact['odoo_id']}");
            $user->update(['res_partner_id' => $serviceItems['owner_id']]);

            $this->syncUserVehicles($user, $serviceItems['vehicle_id']);
            
            Log::warning("Set res_partner_id {$serviceItems['owner_id']} for user {$user['id']}");
        }
    }


    private function syncUserVehicles($user, $vehicleId): void
    {
        $vehicle = \App\Models\Vehicle::where('odoo_id', $vehicleId)->first();
    
        if ($vehicle) {
            $user->vehicles()->detach($vehicle['id']);
            $user->vehicles()->attach([
                $user['id'] => [
                    'vehicle_id' => $vehicle['id'],
                    'service_associated' => true,
                    'updated_at' => now()
                ]
            ]);
        }
    }
    private function processDependentServices($records): void
    {
        $serviceMap = [
            'services_tires' => 'services_tires',
            'tire_brand'     => 'services_tires',
            'tire_model'     => 'services_tires',
            'tire_size'      => 'services_tires',
            'alignment'      => 'alignment',
            'services_oil'   => 'services_oil',
            'service_battery' => 'service_battery',
            'service_balancing' => 'service_balancing',
            'battery_tire_brand' => 'service_battery',
            'battery_tire_model' => 'service_battery',
            'service_oil_brand'  => 'services_oil',
            'filter_brand'       => 'services_oil'
        ];

        $resourceItems = $records->{$serviceMap[$this->service] ?? 'items_services'} ?? [];

        foreach ($resourceItems as $data) {
            if ($this->service !== 'operator' || $data->type !== 'product') {
                $this->processServiceItem($data);
            }
        }
    }

    private function saveServiceSynced($data, bool $procesado_iron, string $from): void
    {
        ServiceSynced::upsert([
            'service_id'     => $data->id,
            'procesado_iron' => $procesado_iron,
            'state'          => $data->state,
            'vehicle_id'     => $data->vehicle->id,
            'not_processed'  => $this->not_processed,
            'from'           => $from
        ], 'service_id');
    }

    public function processedItems(): void
    {
        $records = ServiceSynced::where('procesado_iron', false)->get();
        $to_process = [];

        for ($i = 0; $i < count($records); $i++){
            if ($records[$i]['from'] == 'gwmve'){
                array_push($to_process, [
                    'id' => ($records[$i]['service_id'] - 1000000000),
                    'procesado_iron' => true
                ]);

                $from = $records[$i]['from'];
            }else{
                array_push($to_process, [
                    'id' => $records[$i]['service_id'],
                    'procesado_iron' => true
                ]);

                $from = $records[$i]['from'];
            }
        } 

        if (!empty($to_process)){
            dispatch(new SyncProcessed($to_process, $from));
        }        
    }

    public function postProcessedItems(): void
    {
        $records = ServiceSynced::where('not_processed', false)->get()->toArray();

        if (!empty($records)) {
            dispatch(new SyncPostProcessed($records));
        }
    }
}
