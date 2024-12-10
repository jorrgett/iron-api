<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SyncPostProcessed implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 4;

    /**
     * The maximum number of unhandled exceptions to allow before failing.
     *
     * @var int
     */
    public $maxExceptions = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 240;

    /**
     * The parameters associed to odoo_id
     *
     * @var array
     */

    protected $params;

    /**
     * Create a new job instance.
     */
    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {   
        foreach ($this->params as $item) {

            if ($item['state'] == 'done') {
                DB::select(
                    'call vehicle_summaries_update_vehicle(:vehicle_id, :service_id)',
                    array($item['vehicle_id'], $item['service_id'])
                );

                DB::select(
                    'call oil_change_histories_addnewservice(:service_id)',
                    array($item['service_id'])
                );

                DB::select(
                    'call oil_change_histories_addnewvisits(:vehicle_id, :service_id)',
                    array($item['vehicle_id'], $item['service_id'])
                );

                DB::select(
                    'call vehicle_tire_histories_addnewservice(:vehicle_id, :service_id)',
                    array($item['vehicle_id'], $item['service_id'])
                );

                DB::select(
                    'call vehicle_tire_summaries_addnewservice(:vehicle_id)',
                    array($item['vehicle_id'])
                );
            }

            if ($item['state'] == 'cancelled') {
                DB::select(
                    'call oil_change_histories_addnewservice(:service_id)',
                    array($item['service_id'])
                );
            }
        }
    }
}
