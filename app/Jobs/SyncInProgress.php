<?php

namespace App\Jobs;

use App\Odoo\OdooSync;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SyncInProgress implements ShouldQueue
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
    public $timeout = 3600;

    /**
     * The data from Odoo response
     *
     * @var array
     */
    protected $data;
    protected $modules;
    protected $not_processed;
    protected $procesado_iron;
    protected $from;

    /**
     * Create a new job instance.
     */
    public function __construct($modules, $data, $not_processed, $procesado_iron, $from)
    {
        $this->modules = $modules;
        $this->data = $data;
        $this->not_processed = $not_processed;
        $this->procesado_iron = $procesado_iron;
        $this->from = $from;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {   
        foreach ($this->modules as $service) {
            Log::info("started sync on...{$service}");
            (new OdooSync())->run($service, $this->data, $this->not_processed, $this->procesado_iron, $this->from);
        }
    }
}
