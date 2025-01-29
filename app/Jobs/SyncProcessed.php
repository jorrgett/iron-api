<?php

namespace App\Jobs;

use App\Helpers\OdooHelper;
use App\Models\ServiceSynced;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SyncProcessed implements ShouldQueue
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
     * The parameters to processed
     *
     * @var array
     */
    protected $records;
    protected $from;

    /**
     * Create a new job instance.
     */
    public function __construct($records, $from)
    {
        $this->from = $from;
        $this->records = $records;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        (new OdooHelper($this->from))->sendProcess(['processed' => $this->records]);
    }
}
