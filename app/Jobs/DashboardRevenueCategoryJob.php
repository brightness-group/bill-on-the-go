<?php

namespace App\Jobs;

use App\Models\Tenant\DashboardWidget;
use App\Services\DashboardWidgetService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Tenancy\Facades\Tenancy;
use Illuminate\Bus\Batchable;

class DashboardRevenueCategoryJob extends DashboardWidgetService implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, Batchable;

    public $tenant;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tenant)
    {
        $this->tenant = $tenant;

        Tenancy::setTenant($this->tenant);
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array
     */
    public function tags()
    {
        return ['id:' . $this->tenant->id, 'company:' . $this->tenant->name];
    }

    public function handle()
    {
        $inputs['revenue_category_widget'] = $this->calculateRevenueCategoryDataByFilter();

        $dashboardWidget = DashboardWidget::first();

        !empty($dashboardWidget->id) ? $dashboardWidget->update($inputs) : DashboardWidget::create($inputs);
    }

    private function calculateRevenueCategoryDataByFilter() : array
    {
        // Current Month
        $startDate                  = now()->firstOfMonth()->format('Y-m-d 00:00:00');
        $endDate                    = now()->lastOfMonth()->format('Y-m-d 23:59:59');
        $return['current_month']    = $this->calculateRevenueCategoryData($startDate, $endDate);

        // Current Year
        $startDate              = now()->firstOfYear()->format('Y-m-d 00:00:00');
        $endDate                = now()->lastOfMonth()->format('Y-m-d 23:59:59');
        $return['current_year'] = $this->calculateRevenueCategoryData($startDate, $endDate);

        // Last Month
        $startDate              = now()->subMonth()->firstOfMonth()->format('Y-m-d 00:00:00');
        $endDate                = now()->subMonth()->lastOfMonth()->format('Y-m-d 23:59:59');
        $return['last_month']   = $this->calculateRevenueCategoryData($startDate, $endDate);

        // Last Quarter
        $startDate              = now()->startOfQuarter()->subMonth()->startOfQuarter()->format('Y-m-d 00:00:00');
        $endDate                = now()->startOfQuarter()->subMonth()->endOfQuarter()->format('Y-m-d 23:59:59');
        $return['last_quarter'] = $this->calculateRevenueCategoryData($startDate, $endDate);

        return $return;
    }
}
