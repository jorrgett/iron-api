<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Odoo\OdooSync;
use Illuminate\Bus\Batch;
use App\Helpers\OdooHelper;
use App\Jobs\SyncProcessed;
use App\Jobs\SyncInProgress;
use Illuminate\Http\Request;
use App\Models\ServiceSynced;
use App\Jobs\SyncPostProcessed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Odoo\SyncRequest;

class SyncOdooController extends Controller
{
    protected $modules = [
        'store',
        'vehicle_brand',
        'vehicle_model',
        'vehicle',
        'odometer',
        'service',
        'tire_brand',
        'tire_model',
        'tire_size',
        'services_tires',
        'product_category',
        'product',
        'operator',
        'items_services',
        'alignment',
        'services_oil',
        'service_battery',
        'service_balancing',
        'battery_tire_brand',
        'battery_tire_model',
        'service_oil_brand',
        'filter_brand',
        'inspection_fluid'
    ];

    public function sync(SyncRequest $request): \Illuminate\Http\JsonResponse
    {
        $procesado_iron = (bool) $request->input('procesado_iron', false);
        $state = $request->input('state', 'done');
        $page = (int) $request->input('page', 1);
        $size = (int) $request->input('size', 200);
        $not_processed = (bool) $request->input('not_processed', false);
        $from = (string) $request->input('from', 'autobox');

        $response = $this->getServicesFromOdoo($procesado_iron, $state, $page, $size, $from);

        if ($this->shouldSynchronize($response, $page)) {
            $this->synchronize($response->data, $not_processed, $procesado_iron, $from);

            return response()->json([
                'page' => $page,
                'per_page' => $size,
                'total_records' => $response->total_records,
                'message' => 'The resource synchronization has started'
            ]);
        }

        return response()->json(['message' => 'All resources are synchronized']);
    }

    protected function getServicesFromOdoo(bool $procesado_iron, string $state, int $page, int $size, string $from)
    {
        $params = [
            'filter_contact' => $this->buildFilterParams($procesado_iron, $state),
            'page_number' => $page,
            'per_page' => $size
        ];
        
        return (new OdooHelper($from))->getServices($params);
    }

    protected function buildFilterParams(bool $procesado_iron, string $state): array
    {
        return [
            [
                'field'    => 'date',
                'operator' => '>=',
                'value'    => Carbon::createFromFormat('Y-m-d', '2023-08-01')
            ],
            [
                'field' => 'procesado_iron',
                'operator' => '=',
                'value' => $procesado_iron
            ],
            [
                'field' => 'state',
                'operator' => '=',
                'value' => $state
            ],
            [
                'field'    => 'purchaser_id',
                'operator' => '!=',
                'value' => false 
            ],
            [
                'field' => 'conductor_id',
                'operator' => '!=',
                'value' => false
            ]
        ];
    }

    protected function shouldSynchronize($response, int $page): bool
    {
        return $response->total_records >= 1 && $page <= $response->total_pages;
    }

    protected function synchronize($data, bool $not_processed, bool $procesado_iron, string $from): void
    {
        Bus::batch([
            
            new SyncInProgress($this->modules, $data, $not_processed, $procesado_iron, $from)

        ])->then(function (Batch $batch) {

            (new OdooSync())->processedItems();
            (new OdooSync())->postProcessedItems();

        })->finally(function (Batch $batch) {

            ServiceSynced::truncate();

        })->dispatch();
    }
}

