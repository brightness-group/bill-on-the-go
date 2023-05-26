<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Models\Tenant\ConnectionReport;
use App\Models\Tenant\Customer;
use App\Models\Tenant\DashboardWidget;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DashboardWidgetService
{
    public $dashboardWidgetsData;

    public $quarters = [];

    public $years = [];

    public function __construct()
    {
        // init
        $this->dashboardWidgetsData = [
            'greeting_widget' => [
                'monthly_revenue' => 0,
                'monthly_revenue_percentage' => 0,
            ],
            'statistic_widget' => [
                'statistics_data' => [],
                'email_customers' => [],
                'phone_call_customers' => [],
                'video_call_customers' => [],
                'onsite_customers' => [],
                'vpn_customers' => [],
                'tv_customers' => [],
            ],
            'revenue_category_widget' => [],
            'operating_times_widget' => [
                'monthly_data' => [],
                'quarterly_data' => [],
                'quarterly_filter' => [],
                'monthly_filter' => [],
                'filters' => [
                    'quarters' => [],
                    'years' => []
                ]
            ],
            'turnover_widget' => [
                'months_inputs' => [],
                'without_interval_inputs' => [],
                'with_interval_inputs' => [],
                'more_turnover' => 0,
            ],
            'top_five_customers' => [
                'current_month' => [],
                'current_year' => [],
                'last_month' => [],
                'last_quarter' => [],
                'last_year' => []
            ]
        ];
    }

    /**
     * Get data for all dashboard widgets.
     *
     * @return mixed
     */
    public function getAllWidgetsData()
    {
        $dashboardWidget = DashboardWidget::first();
        if(!empty($dashboardWidget)){
            $this->dashboardWidgetsData = $dashboardWidget;
        }
        return $this->dashboardWidgetsData;
    }

    /**
     * Compute and store data for dashboard widgets.
     *
     * @return void
     */
    public function computeAndStoreAllWidgets()
    {
        $inputs = [
            'greeting_widget' => $this->calculateGreetingsData(),
            'statistic_widget' => [
                'monthly_data' => $this->calculateStatisticsData(),
                'quarterly_data' => $this->calculateStatisticsData(false),
            ],
            'revenue_category_widget' => $this->calculateRevenueCategoryDataByFilter(),
            'operating_times_widget' => $this->calculateOperatingTimesData(),
            'turnover_widget' => [
                'monthly_data' => $this->calculateTurnoverData(),
                'quarterly_data'  => $this->calculateTurnoverDataQuarterly()
            ],
            'top_five_customers' => $this->calculateTopFiveCustomers()
        ];
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

                if (!empty($topCustomer) && count($return['current_month']) < 5) {
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

                if (!empty($topCustomer) && count($return['current_year']) < 5) {
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

                if (!empty($topCustomer) && count($return['last_month']) < 5) {
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

                if (!empty($topCustomer) && count($return['last_quarter']) < 5) {
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

                if (!empty($topCustomer) && count($return['last_year']) < 5) {
                    $return['last_year'][$topCustomer->id] = [
                        'customer_name' => $topCustomer->customer_name,
                        'planned_operating_time' => $plannedOperatingTime,
                        'last_year_actual_operate_time' => $topCustomer->last_year_actual_operate_time,
                        'ot_diff' => $topCustomer->ot_diff
                    ];
                }
            }
        }

        return $return;
    }

    /**
     * Calculate statistics data for dashboard widget.
     *
     * @return array
     */
    private function calculateStatisticsData($isMonthly = true) : array
    {
        if ($isMonthly) {
            $startDate = now()->subMonth()->firstOfMonth()->format('Y-m-d 00:00:00');
            $endDate = now()->subMonth()->lastOfMonth()->format('Y-m-d 23:59:59');
        } else {
            $startDate = now()->subQuarter()->firstOfQuarter()->format('Y-m-d 00:00:00');
            $endDate = now()->subQuarter()->lastOfQuarter()->format('Y-m-d 23:59:59');
        }

        $email_customers = $this->getTopCustomersByContactType($startDate, $endDate,1); // 1 = email
        $phone_call_customers = $this->getTopCustomersByContactType($startDate, $endDate,2); // 2 = phone-call
        $video_call_customers = $this->getTopCustomersByContactType($startDate, $endDate,3); // 3 = video-call
        $onsite_customers = $this->getTopCustomersByContactType($startDate, $endDate,4); // 4 = on site
        $vpn_customers = $this->getTopCustomersByContactType($startDate, $endDate,5); // 5 = vpn-globe
        $tv_customers_data = $this->getTopCustomersByContactType($startDate, $endDate,'tv'); // anydesk

        $topCustomers = collect($tv_customers_data['top_customers'])->pluck('total_price')->toArray();

        return [
            'statistic_widget' => [ // total from all customers by contact type
                'email_customers' => Helper::valueToPercent($email_customers['total_price'],3000),
                'phone_call_customers' => Helper::valueToPercent($phone_call_customers['total_price'],3000),
                'video_call_customers' => Helper::valueToPercent($video_call_customers['total_price'],3000),
                'onsite_customers' => Helper::valueToPercent($onsite_customers['total_price'],3000),
                'vpn_customers' => Helper::valueToPercent($vpn_customers['total_price'],3000),
                'tv_customers' => (!empty($topCustomers)) ? max(Helper::convertArrayValueToPercentage($topCustomers)) : [],
            ],
            'email_customers' => [
                'original' => $email_customers['top_customers'],
                'percentage' => !empty($email_customers['top_customers']) ? Helper::convertArrayValueToPercentage(collect($email_customers['top_customers'])->pluck('total_price')->toArray()) : [],
            ],
            'phone_call_customers' => [
                'original' => $phone_call_customers['top_customers'],
                'percentage' => !empty($phone_call_customers['top_customers']) ? Helper::convertArrayValueToPercentage(collect($phone_call_customers['top_customers'])->pluck('total_price')->toArray()) : [],
            ],
            'video_call_customers' => [
                'original' => $video_call_customers['top_customers'],
                'percentage' => !empty($video_call_customers['top_customers']) ? Helper::convertArrayValueToPercentage(collect($video_call_customers['top_customers'])->pluck('total_price')->toArray()) : [],
            ],
            'onsite_customers' => [
                'original' => $onsite_customers['top_customers'],
                'percentage' => !empty($onsite_customers['top_customers']) ? Helper::convertArrayValueToPercentage(collect($onsite_customers['top_customers'])->pluck('total_price')->toArray()) : [],
            ],
            'vpn_customers' => [
                'original' => $vpn_customers['top_customers'],
                'percentage' => !empty($vpn_customers['top_customers']) ? Helper::convertArrayValueToPercentage(collect($vpn_customers['top_customers'])->pluck('total_price')->toArray()) : [],
            ],
            'tv_customers' => [
                'original' => $tv_customers_data['top_customers'],
                'percentage' => !empty($tv_customers_data['top_customers']) ? Helper::convertArrayValueToPercentage($topCustomers) : [],
            ],
        ];
    }

    public function getTopCustomersByContactType($start_date, $end_date, $contact_type)
    {
        $customers = Customer::select('id', 'bdgogid', 'deleted_at', 'customer_name')
            ->whereHas('connection_reports', function ($q) use ($start_date, $end_date, $contact_type) {
                $q->status(1)
                    ->whereNotNull('tariff_id')
                    ->where('start_date', '>=', $start_date)
                    ->where('end_date', '<=', $end_date);
                if ($contact_type == 'tv') {
                    $q->where('isTV', 1);
                } else {
                    $q->where('contact_type', $contact_type);
                }
            })
            ->with(['connection_reports' => function ($q) use ($start_date, $end_date, $contact_type) {
                $q->select('id', 'bdgogid', 'tariff_id', 'price', 'start_date', 'end_date', 'isTV')
                    ->status(1)
                    ->whereNotNull('tariff_id')
                    ->where('start_date', '>=', $start_date)
                    ->where('end_date', '<=', $end_date);
                if ($contact_type == 'tv') {
                    $q->where('isTV', 1);
                } else {
                    $q->where('contact_type', $contact_type);
                }
            }])
            ->withTrashed()
            ->whereNull('deleted_at')
            ->get();
        foreach ($customers as $tv_customer) {
            $total_price = 0;
            foreach ($tv_customer->connection_reports as $connection_report) {
                $total_price += (int)str_replace(',00', '', $connection_report->price);
            }
            $tv_customer->total_price = $total_price;
            unset($tv_customer->connection_reports, $tv_customer->deleted_at);
        }
        $price = collect($customers)->sortByDesc('total_price')->pluck('total_price')->toArray();
        $total = array_sum($price);
        $top_customers = collect($customers)->sortByDesc('total_price')->take(5)->toArray();
        return [
            'top_customers' => count($top_customers) > 0 ? array_values($top_customers) : [],
            'total_price' => $total,
        ];
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

    /**
     * Calculate revenue category data for dashboard widget.
     *
     * @return array
     */
    public function calculateRevenueCategoryData($startDate, $endDate) : array
    {
        $email_customers        = $this->getTopCustomersByContactType($startDate, $endDate, 1); // 1 = email
        $phone_call_customers   = $this->getTopCustomersByContactType($startDate, $endDate, 2); // 2 = phone-call
        $video_call_customers   = $this->getTopCustomersByContactType($startDate, $endDate, 3); // 3 = video-call
        $onsite_customers       = $this->getTopCustomersByContactType($startDate, $endDate, 4); // 4 = on site
        $vpn_customers          = $this->getTopCustomersByContactType($startDate, $endDate, 5); // 5 = vpn-globe
        $tv_customers_data      = $this->getTopCustomersByContactType($startDate, $endDate, 'tv'); // anydesk

        return [
            'tv_category' => [
                'total_price' => $tv_customers_data['total_price'],
                'percentage' => Helper::valueToPercent($tv_customers_data['total_price'], 3000)
            ],
            'email_category' => [
                'total_price' => $email_customers['total_price'],
                'percentage' => Helper::valueToPercent($email_customers['total_price'], 3000)
            ],
            'phone_call_category' => [
                'total_price' => $phone_call_customers['total_price'],
                'percentage' => Helper::valueToPercent($phone_call_customers['total_price'], 3000)
            ],
            'video_call_category' => [
                'total_price' => $video_call_customers['total_price'],
                'percentage' => Helper::valueToPercent($video_call_customers['total_price'], 3000)
            ],
            'onsite_category' => [
                'total_price' => $onsite_customers['total_price'],
                'percentage' => Helper::valueToPercent($onsite_customers['total_price'], 3000)
            ],
            'vpn_category' => [
                'total_price' => $vpn_customers['total_price'],
                'percentage' => Helper::valueToPercent($vpn_customers['total_price'], 3000)
            ]
        ];
    }

    /**
     * Calculate operating times data monthly for overview operating time dashboard widget.
     *
     * @return array
     */
    public function calculateOperatingTimesMonthly($customers, $quarter = 1): array
    {
        $actualOperationTimeData = $plannedOperationTimeData = [];

        if (!empty($quarter) && $quarter == 1) {
            $quarterStart = reset($this->quarters);
            $quarterEnd   = end($this->quarters);

            $startFrom = (!empty($quarterStart) && is_array($quarterStart)) ? reset($quarterStart) : now()->startOfYear();
            $endTo     = (!empty($quarterEnd) && is_array($quarterEnd)) ? end($quarterEnd) : now()->endOfYear();
        } elseif (!empty($quarter) && !empty($this->quarters[$quarter])) {
            $quarter = $this->quarters[$quarter];

            $startFrom = reset($quarter);
            $endTo     = end($quarter);
        } else {
            $startFrom = now()->startOfYear();
            $endTo     = now()->endOfYear();
        }

        $periods   = CarbonPeriod::create($startFrom, '1 month', $endTo);

        foreach ($customers as $customer) {
            foreach ($periods as $date) {
                $operationTimeData = $actualPlannedOperationData = [];

                $startDate = $date->startOfMonth()->format('Y-m-d 00:00:00');
                $endDate   = $date->endOfMonth()->format('Y-m-d 23:59:59');

                $connectionReports = ConnectionReport::group($customer->bdgogid)
                    ->status(1)
                    ->whereNotNull('tariff_id')
                    ->where('start_date', '>=', $this->filterByStartDate($startDate))
                    ->where('end_date', '<=', $this->filterByEndDate($endDate))
                    ->orderBy('start_date')
                    ->get()
                    ->groupBy(function ($item) {
                        return $item->start_date->format('Y-m-d');
                    });

                foreach ($connectionReports as $connections) {
                    $durationByDay = 0;

                    foreach ($connections as $connection) {
                        $durationByDay += $connection->duration();
                    }

                    $operationTimeData[] = $durationByDay;

                    $actualPlannedOperationData[$connection->start_date->format('m-Y')] = Helper::convertToMinutes($customer->getPlannedOperatingTime($connection->start_date));
                }

                if (!count($operationTimeData)) {
                    $operationTimeData[] = 0;
                }

                if (empty($actualOperationTimeData[$date->startOfMonth()->format('m-y')])) {
                    $actualOperationTimeData[$date->startOfMonth()->format('m-y')] = array_sum($operationTimeData);

                    $plannedOperationTimeData[$date->startOfMonth()->format('m-y')] = array_sum($actualPlannedOperationData);
                } else {
                    $actualOperationTimeData[$date->startOfMonth()->format('m-y')] += array_sum($operationTimeData);

                    $plannedOperationTimeData[$date->startOfMonth()->format('m-y')] += array_sum($actualPlannedOperationData);
                }
            }
        }

        if (!count($actualOperationTimeData)) {
            $actualOperationTimeData[] = 0;
            $plannedOperationTimeData[] = 0;
        } else {
            foreach ($actualOperationTimeData as &$actualOperationTime) {
                $actualOperationTime = Helper::convertMinsToHoursMins($actualOperationTime, ".");
            }

            // sort by month
            uksort($actualOperationTimeData, function($a, $b) {
                return strtotime('01-'.$a) - strtotime('01-'.$b);
            });

            // sort by year
            uksort($actualOperationTimeData, function($a, $b) {
                return strtotime('01-01-'.explode('-',$a)[1]) - strtotime('01-01-'.explode('-',$b)[1]);
            });

            foreach ($plannedOperationTimeData as &$plannedOperationTime) {
                $plannedOperationTime = Helper::convertMinsToHoursMins($plannedOperationTime, '.');
            }
        }

        return [
            'actual_operating_time_data' => array_values($actualOperationTimeData),
            'planned_operating_time_data' => array_values($plannedOperationTimeData),
            'duration_time_data' => array_keys($actualOperationTimeData),
            'duration' => 'monthly'
        ];
    }


    /**
     * Calculate operating times data quarterly for overview operating time dashboard widget.
     *
     * @return array
     */
    public function calculateOperatingTimesQuarterly($customers, $year = 1): array
    {
        // Prepare quarter inputs data
        if (!empty($year)) {
            if ($year == 1) {
                $quarterStart = reset($this->quarters);
                $quarterEnd   = end($this->quarters);

                $startFrom = (!empty($quarterStart) && is_array($quarterStart)) ? reset($quarterStart) : now()->startOfYear();
                $endTo     = (!empty($quarterEnd) && is_array($quarterEnd)) ? end($quarterEnd) : now()->endOfYear();
            } else {
                $date = Carbon::createFromDate($year, 01, 01);

                $startFrom = $date->copy()->startOfYear();
                $endTo     = $date->copy()->endOfYear();
            }
        } else {
            $startFrom = now()->startOfYear();
            $endTo     = now()->endOfYear();
        }

        $periods   = CarbonPeriod::create($startFrom, '3 month', $endTo);

        foreach ($periods as $index => $date) {
            $inputs[$index]['start'] = $date->format('d.m.Y');
            $inputs[$index]['quarter'] = $date->quarter;
            $inputs[$index]['end'] = $date->lastOfQuarter()->format('d.m.Y');
        }

        $quarterData = $plannedOperationTimeData = [];

        foreach ($customers as $customer) {
            foreach ($inputs as $month) {
                $actualOperationTimeData = $actualPlannedOperationData = [];

                $connectionReports = ConnectionReport::group($customer->bdgogid)
                    ->status(1)
                    ->whereNotNull('tariff_id')
                    ->where('start_date', '>=', $this->filterByStartDate($month['start']))
                    ->where('end_date', '<=', $this->filterByEndDate($month['end']))
                    ->orderBy('start_date')
                    ->get()
                    ->groupBy(function ($item) {
                        return $item->start_date->format('Y-m-d');
                    });

                foreach ($connectionReports as $date => $connections) {
                    $durationByDay = 0;

                    foreach ($connections as $connection) {
                        $durationByDay += $connection->duration();
                    }

                    $actualOperationTimeData[] = $durationByDay;

                    $actualPlannedOperationData[$connection->start_date->format('m-Y')] = Helper::convertToMinutes($customer->getPlannedOperatingTime($connection->start_date));
                }

                // Add remaining months POT if not found from connection_reports.
                if (count($actualPlannedOperationData) < 3) {
                    for ($i = count($actualPlannedOperationData); $i < 3; $i++) {
                        $actualPlannedOperationData[] = Helper::convertToMinutes($customer->planned_operating_time);
                    }
                }

                if (!count($actualOperationTimeData)) {
                    $actualOperationTimeData[] = 0;
                }

                $twoDigitYear = substr($month['start'], '8', '2');

                if (empty($quarterData['Q' . $month['quarter'] . ' ' . $twoDigitYear])) {
                    $quarterData['Q' . $month['quarter'] . ' ' . $twoDigitYear] = array_sum($actualOperationTimeData);

                    $plannedOperationTimeData['Q' . $month['quarter'] . ' ' . $twoDigitYear] = array_sum($actualPlannedOperationData);
                } else {
                    $quarterData['Q' . $month['quarter'] . ' ' . $twoDigitYear] += array_sum($actualOperationTimeData);

                    $plannedOperationTimeData['Q' . $month['quarter'] . ' ' . $twoDigitYear] += array_sum($actualPlannedOperationData);
                }
            }
        }

        if (!count($quarterData)) {
            $quarterData[] = 0;
            $plannedOperationTimeData[] = 0;
        } else {
            foreach ($quarterData as &$actualOperationTime) {
                $actualOperationTime = Helper::convertMinsToHoursMins($actualOperationTime, ".");
            }

            foreach ($plannedOperationTimeData as &$plannedOperationTime) {
                // Quarterly multiply by 3.
                // Because as per Susann we define planned operation time monthly so we have to calculate it by 3.
                /* Not needed as we already calculate POT month wise. */
                // $plannedOperationTime = ($plannedOperationTime * 3);

                $plannedOperationTime = Helper::convertMinsToHoursMins($plannedOperationTime, '.');
            }
        }

        return [
            'actual_operating_time_data' => array_values($quarterData),
            'planned_operating_time_data' => array_values($plannedOperationTimeData),
            'duration_time_data' => array_keys($quarterData),
            'duration' => 'quarterly'
        ];
    }

    /**
     * Calculate operating times data by filter by quarterly for overview operating time dashboard widget.
     *
     * @param $totalPlannedOperatingTime
     * @param $customers
     *
     * @return array
     *
     * @author j.mor@brightness-india.com <Jaydeep Mor>
     */
    public function calculateOperatingTimesQuarterlyFilter($customers)
    {
        $data = [];

        if (!empty($this->years)) {
            foreach ($this->years as $year) {
                $data[$year] = $this->calculateOperatingTimesQuarterly($customers, $year);
            }
        }

        return $data;
    }

    /**
     * Calculate operating times data by filter yearly for overview operating time dashboard widget.
     *
     * @param $totalPlannedOperatingTime
     * @param $customers
     *
     * @return array
     *
     * @author j.mor@brightness-india.com <Jaydeep Mor>
     */
    public function calculateOperatingTimesMonthlyFilter($customers)
    {
        $data = [];

        if (!empty($this->quarters)) {
            foreach ($this->quarters as $quarter => $timeObj) {
                $data[$quarter] = $this->calculateOperatingTimesMonthly($customers, $quarter);
            }
        }

        return $data;
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

    /**
     * Calculate quarters.
     * @param void
     *
     * @return null (Method will set quarters & years property.)
     *
     * @author j.mor@brightness-india.com <Jaydeep Mor>
     */
    public function setQuarters()
    {
        // Find oldest customer.
        $oldCustomer = Customer::orderBy("created_at", "ASC")->get()->take(1)->first();

        if (empty($oldCustomer) || empty($oldCustomer->created_at)) {
            return false;
        }

        $now = now();

        $createdAt = $oldCustomer->created_at;

        $firstOfQuarter = $createdAt->clone()->firstOfQuarter();

        $lastOfQuarter = $createdAt->clone()->lastOfQuarter();

        // Chart should represent only 1.5 years of data as per Susann.
        $endDate              = $now->clone()->firstOfMonth();
        $beforeEighteenMonths = $endDate->clone()->subMonths(18);

        if ($beforeEighteenMonths->gt($createdAt)) {
            $createdAt = $beforeEighteenMonths;

            $firstOfQuarter = $createdAt->clone()->firstOfQuarter();

            $lastOfQuarter = $createdAt->clone()->lastOfQuarter();
        }

        $this->quarters[$createdAt->quarter . $createdAt->format(' - Y')] = [$firstOfQuarter, $lastOfQuarter];

        $this->years[$createdAt->format('Y')] = $createdAt->format('Y');

        $loop = !$lastOfQuarter->isCurrentQuarter();

        while ($loop) {
            $carbon = new Carbon($firstOfQuarter);

            $addQuarter = $carbon->addQuarter();

            $firstOfQuarter = $addQuarter->clone()->firstOfQuarter();

            $lastOfQuarter = $addQuarter->clone()->lastOfQuarter();

            $this->quarters[$addQuarter->quarter . $addQuarter->format(' - Y')] = [$firstOfQuarter, $lastOfQuarter];

            $this->years[$addQuarter->format('Y')] = $addQuarter->format('Y');

            $loop = !$carbon->isCurrentQuarter();
        }

        return null;
    }

    /**
     * Calculate turnover data first and end date.
     *
     *
     * @return array
     *
     * @author j.mor@brightness-india.com <Jaydeep Mor>
     */
    public function getTurnoverDataDates(): array
    {
        $firstConnectionReport = ConnectionReport::status(1)
            ->whereNotNull('tariff_id')
            ->orderBy('start_date')
            ->first();

        if (!empty($firstConnectionReport) && !empty($firstConnectionReport->start_date) && $firstConnectionReport->start_date instanceof Carbon) {

            $now = now();

            $createdAt = $firstConnectionReport->start_date;

            // Set months.
            $startDate = $createdAt->clone()->firstOfMonth();

            $endDate   = $now->clone()->firstOfMonth();

            // Chart should represent only 1.5 years of data as per Susann.
            $beforeEighteenMonths = $endDate->clone()->subMonths(18);

            if ($beforeEighteenMonths->gt($startDate)) {
                $startDate = $beforeEighteenMonths;
            }

            $period  = CarbonPeriod::create($startDate, '1 month', $endDate);

            foreach ($period as $month) {
                $firstOfMonth = $month->clone()->firstOfMonth();

                $lastOfMonth  = $month->clone()->lastOfMonth();

                $months[$month->format('m - Y')] = ['first' => $firstOfMonth, 'last' => $lastOfMonth];
            }

            // Set quarters.
            $firstOfQuarter = $startDate->clone()->firstOfQuarter();

            $lastOfQuarter = $startDate->clone()->lastOfQuarter();

            $quarters['Q' . $startDate->quarter . $startDate->format(' - Y')] = ['first' => $firstOfQuarter, 'last' => $lastOfQuarter];

            $loop = !$lastOfQuarter->isCurrentQuarter();

            while ($loop) {
                $carbon = new Carbon($firstOfQuarter);

                $addQuarter = $carbon->addQuarter();

                $firstOfQuarter = $addQuarter->clone()->firstOfQuarter();

                $lastOfQuarter = $addQuarter->clone()->lastOfQuarter();

                $quarters['Q' . $addQuarter->quarter . $addQuarter->format(' - Y')] = ['first' => $firstOfQuarter, 'last' => $lastOfQuarter];

                $loop = !$carbon->isCurrentQuarter();
            }
        } else {
            $months[] = $quarters[] = ['first' => 0, 'last' => 0];
        }

        return [
            'months' => $months,
            'quarters' => $quarters
        ];
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

    /**
     * Calculate turnover data quarterly for dashboard widget.
     *
     * @param $totalPlannedOperatingTime
     * @param $customers
     *
     * @return array
     *
     * @author j.mor@brightness-india.com <Jaydeep Mor>
     */
    public function calculateTurnoverDataQuarterly(): array
    {
        return $this->calculateTurnoverData(false);
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
        //$data['monthly_revenue'] = Helper::numberFormatShort($monthly_revenue['with_interval_price_sum']);
        $data['monthly_revenue'] = $monthly_revenue['with_interval_price_sum'];
        return $data;
    }

    /**
     * Calculate turnover by given date range.
     *
     * @param $start_date
     * @param $end_date
     * @return array|int[]
     */
    public function calculateTurnOverByDateRange($startDate, $endDate): array
    {
        $withoutIntervalSum      = 0;
        $withIntervalSum         = 0;
        $withoutIntervalPriceSum = 0;
        $withIntervalPriceSum    = 0;
        $intervalTime            = 0;
        $intervalPrice           = 0;

        $customers = Customer::select('id', 'deleted_at', 'bdgogid')->withTrashed()->whereNull('deleted_at')->get();

        foreach ($customers as $customer) {
            ConnectionReport::group($customer->bdgogid)
                ->status(1)
                ->whereNotNull('tariff_id')
                ->where('start_date', '>=', $startDate)
                ->where('end_date', '<=', $endDate)
                ->chunk(200, function ($connections) use (&$withoutIntervalSum, &$withIntervalSum, &$intervalTime, &$withoutIntervalPriceSum, &$withIntervalPriceSum, &$intervalPrice) {
                    foreach ($connections as $connection) {
                        $calculateIntervalIndividualRes = $connection->calculateIntervalWithPriceIndividual();

                        $withoutIntervalSum += $calculateIntervalIndividualRes['without_interval'];
                        $withIntervalSum += $calculateIntervalIndividualRes['with_interval'];
                        $intervalTime += $calculateIntervalIndividualRes['interval_time'];
                        $withoutIntervalPriceSum += $calculateIntervalIndividualRes['without_interval_price'];
                        $withIntervalPriceSum += $calculateIntervalIndividualRes['with_interval_price'];
                        $intervalPrice += $calculateIntervalIndividualRes['interval_price'];
                    }
                });
        }

        return [
            'without_interval_sum' => $withoutIntervalSum,
            'with_interval_sum' => $withIntervalSum,
            'interval_time' => $intervalTime,
            'without_interval_price_sum' => $withoutIntervalPriceSum,
            'with_interval_price_sum' => $withIntervalPriceSum,
            'interval_price' => $intervalPrice
        ];
    }

    private function filterByStartDate($value)
    {
        $start_time = new \DateTime($value);
        $start_time = $start_time->format('d.m.Y 00:00:00');
        return date_create($start_time);
    }

    private function filterByEndDate($value)
    {
        $end_time = new \DateTime($value);
        $end_time = $end_time->format('d.m.Y 23:59:59');
        return date_create($end_time);
    }
}
