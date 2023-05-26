<?php

namespace App\Jobs;

use App\Helpers\Helper;
use App\Models\Tenant\User;
use App\Notifications\Admin\ComputeDashboardWidgetsJobFails;
use App\Notifications\Admin\ComputeDashboardWidgetsJobSuccess;
use Illuminate\Support\Facades\Cache;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Throwable;

class ComputeDashboardWidgetsJob
{
    public $tenant;

    public $exception;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tenant)
    {
        $this->tenant = $tenant;
    }

    public function handle()
    {
        Cache::lock('computeDashboardIndex-' . $this->tenant->id)->forceRelease();

        if (!empty($this->tenant->batch_id)) {
            $batch = Helper::checkDashboardRunningBatch($this->tenant->batch_id);

            if (!empty($batch) && !$batch->finished()) {
                return false;
            }
        }

        $batch = Bus::batch([
            new DashboardGreetingJob($this->tenant),
            new DashboardStatisticJob($this->tenant),
            new DashboardRevenueCategoryJob($this->tenant),
            new DashboardOperatingTimeJob($this->tenant),
            new DashboardTurnoverJob($this->tenant),
            new DashboardTopFiveCustomerJob($this->tenant)
        ])
        ->then(function (Batch $batch) {
            User::all()->reject(function ($user) {
                return !$user->hasRole('Admin');
            })
            ->map(function ($user) {
                $user->notify(new ComputeDashboardWidgetsJobSuccess($user));
            });
        })
        ->catch(function (Batch $batch, Throwable $exception) {
            $this->exception = $exception->getCode() . ': ' . $exception->getMessage();

            Log::error('ComputeDashboardWidgetsJobFails=>', ['Exception '=> $this->exception]);

            User::all()->reject(function ($user) {
                return !$user->hasRole('Admin');
            })
            ->map(function ($user) {
                $user->notify(new ComputeDashboardWidgetsJobFails($user, $this->exception));
            });
        })
        ->name(__('locale.Dashboard synchronization batch', ['company' => $this->tenant->name]))
        ->dispatch();

        $this->tenant->batch_id = $batch->id;

        $this->tenant->save();

        return $batch;
    }
}
