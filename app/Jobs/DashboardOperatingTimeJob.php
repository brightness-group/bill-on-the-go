<?php

namespace App\Jobs;

use App\Models\Tenant\Customer;
use App\Models\Tenant\DashboardWidget;
use App\Services\DashboardWidgetService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Tenancy\Facades\Tenancy;
use Illuminate\Bus\Batchable;

class DashboardOperatingTimeJob extends DashboardWidgetService implements ShouldQueue
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
        $inputs['operating_times_widget'] = $this->calculateOperatingTimesData();

        $dashboardWidget = DashboardWidget::first();

        !empty($dashboardWidget->id) ? $dashboardWidget->update($inputs) : DashboardWidget::create($inputs);
    }

    /**
     * Calculate operating times data for overview operating time dashboard widget.
     *
     * @return array
     */
    private function calculateOperatingTimesData(): array
    {
        $customers = Customer::select('id', 'bdgogid', 'deleted_at', 'planned_operating_time')->get();

        $this->setQuarters();

        return [
            'monthly_data' => $this->calculateOperatingTimesMonthly($customers),
            'quarterly_data' => $this->calculateOperatingTimesQuarterly($customers),
            'quarterly_filter' => $this->calculateOperatingTimesQuarterlyFilter( $customers),
            'monthly_filter' => $this->calculateOperatingTimesMonthlyFilter( $customers),
            'filters' => [
                'quarters' => $this->quarters,
                'years' => $this->years
            ]
        ];
    }
}
