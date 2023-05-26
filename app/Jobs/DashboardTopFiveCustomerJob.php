<?php

namespace App\Jobs;

use App\Helpers\Helper;
use App\Models\Tenant\Customer;
use App\Models\Tenant\DashboardWidget;
use App\Services\DashboardWidgetService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Tenancy\Facades\Tenancy;
use Illuminate\Bus\Batchable;

class DashboardTopFiveCustomerJob extends DashboardWidgetService implements ShouldQueue
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
        $inputs['top_five_customers'] = $this->calculateTopFiveCustomers();

        $dashboardWidget = DashboardWidget::first();

        !empty($dashboardWidget->id) ? $dashboardWidget->update($inputs) : DashboardWidget::create($inputs);
    }

    /**
     * Calculate top 5 customers with extra demands.
     * 
     * @author Jaydeep Mor <j.mor@brightness-india.com>
     */
    private function calculateTopFiveCustomers()
    {
        $now        = now();
        $startYear  = $now->clone()->startOfYear();
        $dateRange  = CarbonPeriod::create($startYear, '1 month', $now);
        $lastMonth  = $now->clone()->subMonth(1);
        $firstDayOfLastQuarter = now()->startOfQuarter()->subMonth()->startOfQuarter();
        $lastDayOfLastQuarter  = now()->startOfQuarter()->subMonth()->endOfQuarter();
        $dateRange             = CarbonPeriod::create($firstDayOfLastQuarter, '1 month', $lastDayOfLastQuarter);

        $customers = Customer::all();

        $return = [
            'current_month' => [],
            'current_year' => [],
            'last_month' => [],
            'last_quarter' => [],
            'last_year' => []
        ];

        if (!empty($customers) && !$customers->isEmpty()) {
            foreach ($customers as $customer) {
                // Current month.
                $plannedOperatingTime = Helper::convertToMinutes($customer->getPlannedOperatingTime($now));

                $topCustomer = $customer->getTopCustomer($plannedOperatingTime, 'curr_month_actual_operate_time');

                if (!empty($topCustomer)) {
                    $return['current_month'][$topCustomer->id] = [
                        'customer_name' => $topCustomer->customer_name,
                        'planned_operating_time' => $plannedOperatingTime,
                        'curr_month_actual_operate_time' => $topCustomer->curr_month_actual_operate_time,
                        'ot_diff' => $topCustomer->ot_diff
                    ];
                }

                // Current year.
                $plannedOperatingTimes = [];
                $plannedOperatingTime  = 0;

                foreach ($dateRange as $date) {
                    $plannedOperatingTimes[] = Helper::convertToMinutes($customer->getPlannedOperatingTime($date));
                }

                $plannedOperatingTime = array_sum($plannedOperatingTimes);

                $topCustomer = $customer->getTopCustomer($plannedOperatingTime, 'current_year_actual_operate_time');

                if (!empty($topCustomer)) {
                    $return['current_year'][$topCustomer->id] = [
                        'customer_name' => $topCustomer->customer_name,
                        'planned_operating_time' => $plannedOperatingTime,
                        'current_year_actual_operate_time' => $topCustomer->current_year_actual_operate_time,
                        'ot_diff' => $topCustomer->ot_diff
                    ];
                }

                // Last month.
                $plannedOperatingTime = Helper::convertToMinutes($customer->getPlannedOperatingTime($lastMonth));

                $topCustomer = $customer->getTopCustomer($plannedOperatingTime, 'last_month_actual_operate_time');

                if (!empty($topCustomer)) {
                    $return['last_month'][$topCustomer->id] = [
                        'customer_name' => $topCustomer->customer_name,
                        'planned_operating_time' => $plannedOperatingTime,
                        'last_month_actual_operate_time' => $topCustomer->last_month_actual_operate_time,
                        'ot_diff' => $topCustomer->ot_diff
                    ];
                }

                // Last quarter.
                $plannedOperatingTimes = [];
                $plannedOperatingTime  = 0;

                foreach ($dateRange as $date) {
                    $plannedOperatingTimes[] = Helper::convertToMinutes($customer->getPlannedOperatingTime($date));
                }

                $plannedOperatingTime = array_sum($plannedOperatingTimes);

                $topCustomer = $customer->getTopCustomer($plannedOperatingTime, 'last_quarter_actual_operate_time');

                if (!empty($topCustomer)) {
                    $return['last_quarter'][$topCustomer->id] = [
                        'customer_name' => $topCustomer->customer_name,
                        'planned_operating_time' => $plannedOperatingTime,
                        'last_quarter_actual_operate_time' => $topCustomer->last_quarter_actual_operate_time,
                        'ot_diff' => $topCustomer->ot_diff
                    ];
                }

                // Last year.
                $firstDayOfLastYear    = Carbon::createFromFormat('Y-m-d', now()->subYear()->format('Y') . '-01-01');
                $lastDayOfLastYear     = Carbon::createFromFormat('Y-m-d', now()->subYear()->format('Y') . '-12-30');
                $dateRange             = CarbonPeriod::create($firstDayOfLastYear, '1 month', $lastDayOfLastYear);
                $plannedOperatingTimes = [];
                $plannedOperatingTime  = 0;

                foreach ($dateRange as $date) {
                    $plannedOperatingTimes[] = Helper::convertToMinutes($customer->getPlannedOperatingTime($date));
                }

                $plannedOperatingTime = array_sum($plannedOperatingTimes);

                $topCustomer = $customer->getTopCustomer($plannedOperatingTime, 'last_year_actual_operate_time');

                if (!empty($topCustomer)) {
                    $return['last_year'][$topCustomer->id] = [
                        'customer_name' => $topCustomer->customer_name,
                        'planned_operating_time' => $plannedOperatingTime,
                        'last_year_actual_operate_time' => $topCustomer->last_year_actual_operate_time,
                        'ot_diff' => $topCustomer->ot_diff
                    ];
                }
            }

            $return['current_month']    = collect($return['current_month'])->sortBy('ot_diff')->reverse()->slice(0, 5)->toArray();
            $return['current_year']     = collect($return['current_year'])->sortBy('ot_diff')->reverse()->slice(0, 5)->toArray();
            $return['last_month']       = collect($return['last_month'])->sortBy('ot_diff')->reverse()->slice(0, 5)->toArray();
            $return['last_quarter']     = collect($return['last_quarter'])->sortBy('ot_diff')->reverse()->slice(0, 5)->toArray();
            $return['last_year']        = collect($return['last_year'])->sortBy('ot_diff')->reverse()->slice(0, 5)->toArray();
        }

        return $return;
    }
}
