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

class DashboardGreetingJob extends DashboardWidgetService implements ShouldQueue
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
        $inputs['greeting_widget'] = $this->calculateGreetingsData();

        $dashboardWidget = DashboardWidget::first();

        !empty($dashboardWidget->id) ? $dashboardWidget->update($inputs) : DashboardWidget::create($inputs);
    }

    /**
     * Calculate greetings data for dashboard widget.
     *
     * @return int[]
     */
    private function calculateGreetingsData() : array
    {
        $from = now()->firstOfMonth()->setTime(0, 0, 0);
        $to = now()->lastOfMonth()->setTime(23, 59, 59);
        $today = now()->setTime(23, 59, 59);
        $data = ['monthly_revenue_percentage' => 0];

        $current_month_revenue = $this->calculateTurnOverByDateRange($from, $today);
        $previous_month_revenue = $this->calculateTurnOverByDateRange($from->subMonth(), $today->subMonth());
        $monthly_revenue = $this->calculateTurnOverByDateRange($from, $to);

        if (isset($current_month_revenue['with_interval_price_sum']) && isset($previous_month_revenue['with_interval_price_sum'])) {
            if ($previous_month_revenue['with_interval_price_sum'] != 0) {
                $data['monthly_revenue_percentage'] = str_replace(".00", "",number_format(100 * (($current_month_revenue['with_interval_price_sum'] - $previous_month_revenue['with_interval_price_sum']) / $previous_month_revenue['with_interval_price_sum']),2));
            } else if ($current_month_revenue['with_interval_price_sum'] != 0) {
                $data['monthly_revenue_percentage'] = 100;
            }
        }

        $data['monthly_revenue'] = $monthly_revenue['with_interval_price_sum'];

        return $data;
    }
}
