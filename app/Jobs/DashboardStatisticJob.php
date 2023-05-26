<?php

namespace App\Jobs;

use App\Helpers\Helper;
use App\Models\Tenant\DashboardWidget;
use App\Services\DashboardWidgetService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Tenancy\Facades\Tenancy;
use Illuminate\Bus\Batchable;

class DashboardStatisticJob extends DashboardWidgetService implements ShouldQueue
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
        $inputs['statistic_widget']['monthly_data'] = $this->calculateStatisticsData();
        $inputs['statistic_widget']['quarterly_data'] = $this->calculateStatisticsData(false);

        $dashboardWidget = DashboardWidget::first();

        !empty($dashboardWidget->id) ? $dashboardWidget->update($inputs) : DashboardWidget::create($inputs);
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
}
