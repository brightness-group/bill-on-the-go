<?php

namespace App\Jobs;

use App\Models\Tenant\User;
use App\Notifications\Admin\CalculateActualOperatingTimeJobFails;
use App\Notifications\Admin\CalculateActualOperatingTimeJobSuccess;
use App\Services\CalculateCustomerOperatingTime;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Tenancy\Facades\Tenancy;
use Throwable;

class CalculateCustomerActualOperatingTimeJob implements ShouldQueue
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
        if (!$this->calculate()) {
            $this->release(1800);
        } else {
            $this->success();
        }
    }

    public function calculate(): bool
    {
        Tenancy::setTenant($this->tenant);
        $calculateCustomerOperatingTimeService = new CalculateCustomerOperatingTime();
        $calculateCustomerOperatingTimeService->calculateActualOperatingTime();
        return true;
    }

    public function success(): void
    {
        Tenancy::setTenant($this->tenant);
        User::all()->reject(function ($user) {
            return !$user->hasRole('Admin');
        })
            ->map(function ($user) {
                $user->notify(new CalculateActualOperatingTimeJobSuccess($user));
            });
    }

    /**
     * Handle a job failure.
     *
     * @param \Throwable $exception
     * @return void
     */
    public function failed(Throwable $exception): void
    {
        $this->exception = $exception->getCode() . ': ' . $exception->getMessage();
        Tenancy::setTenant($this->tenant);
        User::all()->reject(function ($user) {
            return !$user->hasRole('Admin');
        })
            ->map(function ($user) {
                $user->notify(new CalculateActualOperatingTimeJobFails($user, $this->exception));
            });
    }

}
