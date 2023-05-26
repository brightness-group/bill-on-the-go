<?php

namespace App\Jobs;

use App\Services\UpdateOverlapsTariff;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Tenancy\Facades\Tenancy;

class UpdateOverlapsTariffConnections implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tenant;

    public $exception;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The maximum number of unhandled exceptions to allow before failing.
     *
     * @var int
     */
    public $maxExceptions = 3;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tenant)
    {
        $this->tenant = $tenant;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!$this->checkAndUpdateForTariffOverlap()) {
            $this->release(1800);
        }
    }

    private function checkAndUpdateForTariffOverlap(): bool
    {
        Tenancy::setTenant($this->tenant);
        $updateOverlapsTariffConnService = new UpdateOverlapsTariff();
        $updateOverlapsTariffConnService->checkAndUpdateBorderLineEmergence();
        return true;
    }
}
