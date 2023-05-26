<?php

namespace App\Jobs;

use App\Models\Tenant\Customer;
use App\Models\Tenant\DashboardWidget;
use App\Services\DashboardWidgetService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Tenancy\Facades\Tenancy;
use Illuminate\Bus\Batchable;

class DashboardTurnoverJob extends DashboardWidgetService implements ShouldQueue
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
        $inputs['turnover_widget']['monthly_data'] = $this->calculateTurnoverData();
        $inputs['turnover_widget']['quarterly_data'] = $this->calculateTurnoverDataQuarterly();

        $dashboardWidget = DashboardWidget::first();

        !empty($dashboardWidget->id) ? $dashboardWidget->update($inputs) : DashboardWidget::create($inputs);
    }

    /**
     * Calculate turnover data monthly for dashboard widget.
     *
     * @return array
     */
    private function calculateTurnoverData($isMonthly = true): array
    {
        $dates = $this->getTurnoverDataDates();

        if ($isMonthly) {
            $period = $dates['months'];
        } else {
            $period = $dates['quarters'];
        }

        $monthsInputs                 = [];
        $totalWithoutIntervalPriceSum = 0;
        $totalWithIntervalPriceSum    = 0;
        $months                       = 0;

        foreach ($period as $key => $date) {
            if (!($date['first'] instanceof Carbon) || !($date['last'] instanceof Carbon)) {
                continue;
            }

            $startDate = $date['first']->format('Y-m-d 00:00:00');
            $endDate   = $date['last']->format('Y-m-d 23:59:59');

            // Get turnover by start and end dates.
            $result    = $this->calculateTurnOverByDateRange($startDate, $endDate);

            $withoutIntervalInputs[] = $result['without_interval_sum'];
            $withIntervalInputs[]    = $result['with_interval_sum'];
            $intervalTime[]          = $result['interval_time'];

            $withoutIntervalPriceSum[]      = $result['without_interval_price_sum'];
            $withIntervalPriceSum[]         = $result['with_interval_price_sum'];
            $intervalPrice[]                = $result['interval_price'];
            $totalWithoutIntervalPriceSum   += $result['without_interval_price_sum'];
            $totalWithIntervalPriceSum      += $result['with_interval_price_sum'];

            if ($result['with_interval_sum'] > 0) {
                $withoutIntervalPercentage[] = round(($result['without_interval_sum'] / $result['with_interval_sum']) * 100);
                $withIntervalPercentage[]    = round(($result['with_interval_sum'] / $result['with_interval_sum']) * 100);
                $intervalPercentage[]        = round(($result['interval_time'] / $result['with_interval_sum']) * 100);
            } else {
                $withoutIntervalPercentage[] = 0;
                $withIntervalPercentage[]    = 0;
                $intervalPercentage[]        = 0;
            }

            if ($isMonthly) {
                $months = ($months + 1);

                $monthsInputs[] = $date['first']->format('m-y');
            } else {
                $months = ($months + 3);

                $monthsInputs[] = 'Q' . $date['first']->quarter . $date['first']->format('-y');
            }
        }

        $turnoverAverage = ($totalWithIntervalPriceSum > 0) ? $totalWithIntervalPriceSum / ($months + 1) : 0;
        $moreTurnover    = ($totalWithIntervalPriceSum - $totalWithoutIntervalPriceSum);

        return [
            'months_inputs' => $monthsInputs,
            'without_interval_inputs' => $withoutIntervalInputs??[],
            'with_interval_inputs' => $withIntervalInputs??[],
            'interval_time' => $intervalTime??[],
            'more_turnover' => $moreTurnover,
            'turnover_average' => $turnoverAverage,
            'without_interval_price_sum' => $withoutIntervalPriceSum??[],
            'with_interval_price_sum' => $withIntervalPriceSum??[],
            'interval_price' => $intervalPrice??[],
            'without_interval_percentage' => $withoutIntervalPercentage??[],
            'with_interval_percentage' => $withIntervalPercentage??[],
            'interval_percentage' => $intervalPercentage??[],
            'duration' => ($isMonthly) ? 'monthly' : 'quarterly'
        ];
    }
}
