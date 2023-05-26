<?php

namespace App\Services;


use App\Helpers\Helper;
use App\Models\Tenant\Customer;
use Carbon\Carbon;

class CalculateCustomerOperatingTime
{
    public function __construct()
    {
    }

    private function filterByStartDate($value)
    {
        $startTime = new \DateTime($value);

        $startTime = $startTime->format('d.m.Y 00:00:00');

        return date_create($startTime);
    }

    private function filterByEndDate($value)
    {
        $endTime = new \DateTime($value);

        $endTime = $endTime->format('d.m.Y 23:59:59');

        return date_create($endTime);
    }

    public function updateCustomersByDateRange($start_date, $end_date, $type = 'curr_month_actual_operate_time'): void
    {
        $customers = Customer::select('id', 'bdgogid')
                             ->whereHas('connection_reports', function ($query) use ($start_date, $end_date) {
                                 $query->status(1)
                                       ->whereNotNull('tariff_id')
                                       ->whereDate('start_date', '>=', $this->filterByStartDate($start_date))
                                       ->whereDate('end_date', '<=', $this->filterByEndDate($end_date));
                             })
                             ->with(['connection_reports' => function ($query) use ($start_date, $end_date) {
                                 $query->select('bdgogid', 'tariff_id', 'start_date', 'end_date', 'billing_state', 'deleted_at', 'booked', 'overlaps_user')
                                       ->status(1)
                                       ->whereNotNull('tariff_id')
                                       ->whereDate('start_date', '>=', $this->filterByStartDate($start_date))
                                       ->whereDate('end_date', '<=', $this->filterByEndDate($end_date));
                            }])
                            ->get();

        foreach ($customers as $customer) {
            $sum = 0;

            foreach ($customer->connection_reports as $connection_report) {
                $sum += $connection_report->duration();
            }

            $customer->update([$type => $sum]);
        }
    }

    public function calculateActualOperatingTime(): void
    {
        // First set zero '0' for all customers.
        Customer::query()->update([
            'curr_month_actual_operate_time' => '0',
            'last_month_actual_operate_time' => '0',
            'last_quarter_actual_operate_time' => '0',
            'last_year_actual_operate_time' => '0',
            'current_year_actual_operate_time' => '0'
        ]);

        // current month customer actual time update
        $firstDayOfMonth = Carbon::now()->firstOfMonth()->format('Y-m-d H:i:s');
        $today = Carbon::now()->format('Y-m-d 23:59:59');
        $this->updateCustomersByDateRange($firstDayOfMonth, $today);

        // last month customer actual time update
        $firstDayOfLastMonth = Carbon::now()->subMonth()->firstOfMonth()->format('Y-m-d H:i:s');
        $lastDayOfLastMonth = Carbon::now()->subMonth()->lastOfMonth()->format('Y-m-d 23:59:59');
        $this->updateCustomersByDateRange($firstDayOfLastMonth, $lastDayOfLastMonth, 'last_month_actual_operate_time');

        // last quarter customer actual time update
        $firstDayOfLastQuarter = Carbon::now()->startOfQuarter()->subMonth()->startOfQuarter()->format('Y-m-d H:i:s');
        $lastDayOfLastQuarter = Carbon::now()->startOfQuarter()->subMonth()->endOfQuarter()->format('Y-m-d 23:59:59');
        $this->updateCustomersByDateRange($firstDayOfLastQuarter, $lastDayOfLastQuarter, 'last_quarter_actual_operate_time');

        // Last year actual time.
        $firstDayOfLastYear = Carbon::createFromFormat('Y-m-d', Carbon::now()->subYear()->format('Y') . '-01-01')->format('Y-m-d H:i:s');
        $lastDayOfLastYear = Carbon::createFromFormat('Y-m-d', Carbon::now()->subYear()->format('Y') . '-12-30')->format('Y-m-d 23:59:59');
        $this->updateCustomersByDateRange($firstDayOfLastYear, $lastDayOfLastYear, 'last_year_actual_operate_time');

        // Current year actual time.
        $firstDayOfCurrentYear = Carbon::createFromFormat('Y-m-d', Carbon::now()->format('Y') . '-01-01')->format('Y-m-d H:i:s');
        $lastDayOfCurrentYear = Carbon::createFromFormat('Y-m-d', Carbon::now()->format('Y') . '-12-30')->format('Y-m-d 23:59:59');
        $this->updateCustomersByDateRange($firstDayOfCurrentYear, $lastDayOfCurrentYear, 'current_year_actual_operate_time');
    }

}
