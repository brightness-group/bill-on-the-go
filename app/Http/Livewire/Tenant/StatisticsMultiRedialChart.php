<?php

namespace App\Http\Livewire\Tenant;

use App\Helpers\CoreHelpers;
use App\Services\DashboardWidgetService;
use Livewire\Component;

class StatisticsMultiRedialChart extends Component
{
    public $statistics_data;
    public $email_customers;
    public $phone_call_customers;
    public $video_call_customers;
    public $onsite_customers;
    public $vpn_customers;
    public $anydesk_customers;
    public array $colors = ['#fdac41', '#39da8a', '#5a8dee', '#ff5b5c', '#00cfdd'];
    public string $contact_type = 'anydesk';
    public $dashboard_widget;
    public $statistic_widget;
    public int $durationMonths = 1;

    public function mount($statistic_widget = [])
    {
        $this->statistics_data = !empty($statistic_widget['statistics_data']) ? $statistic_widget['statistics_data'] : [];
        $this->email_customers = !empty($statistic_widget['email_customers']) ? $statistic_widget['email_customers'] : [];
        $this->phone_call_customers = !empty($statistic_widget['phone_call_customers']) ? $statistic_widget['phone_call_customers'] : [];
        $this->video_call_customers = !empty($statistic_widget['video_call_customers']) ? $statistic_widget['video_call_customers'] : [];
        $this->onsite_customers = !empty($statistic_widget['onsite_customers']) ? $statistic_widget['onsite_customers'] : [];
        $this->vpn_customers = !empty($statistic_widget['vpn_customers']) ? $statistic_widget['vpn_customers'] : [];
        $this->anydesk_customers = !empty($statistic_widget['tv_customers']) ? $statistic_widget['tv_customers'] : [];

        $this->durationMonths = CoreHelpers::getPreviousState('dashboard', 'statisticsRadialDurationMonth', $this->durationMonths);
        $this->contact_type = CoreHelpers::getPreviousState('dashboard', 'statisticsRadialContactType', $this->contact_type);

        $dashboardWidgetService = new DashboardWidgetService();
        $dashboardWidget = $dashboardWidgetService->getAllWidgetsData();
        $this->dashboard_widget = gettype($dashboardWidget) == 'object' ? collect($dashboardWidget)->toArray() : $dashboardWidget;

    }

    public function render()
    {
        return view('livewire.tenant.statistics-multi-redial-chart');
    }

    public function updatedContactType()
    {
        $dashboardWidget = $this->dashboard_widget;

        if ($this->contact_type == 'email') {
            $selected_contact_type = 'email_customers';
        } elseif ($this->contact_type == 'phonecall') {
            $selected_contact_type = 'phone_call_customers';
        } elseif ($this->contact_type == 'videocall') {
            $selected_contact_type = 'video_call_customers';
        } elseif ($this->contact_type == 'onsite') {
            $selected_contact_type = 'onsite_customers';
        } elseif ($this->contact_type == 'vpn') {
            $selected_contact_type = 'vpn_customers';
        } elseif ($this->contact_type == 'anydesk') {
            $selected_contact_type = 'tv_customers';
        }

        CoreHelpers::setMultipleState('dashboard', [
            'statisticsRadialDurationMonth' => $this->durationMonths,
            'statisticsRadialContactType' => $this->contact_type,
            'statisticsRadialContactTypeName' => $selected_contact_type
        ]);

        $type = $this->durationMonths == 1 ? 'monthly_data' : 'quarterly_data';

        $statisticWidget = (!empty($dashboardWidget['statistic_widget'][$type])) ? $dashboardWidget['statistic_widget'][$type] : [];

        $statisticWidget['series_data'] = !empty($statisticWidget[$selected_contact_type]['percentage'])
            ? array_reverse($statisticWidget[$selected_contact_type]['percentage'])
            : [];

        $statisticWidget['total_price'] = (!empty($statisticWidget[$selected_contact_type]['original']))
            ? array_reverse(collect($statisticWidget[$selected_contact_type]['original'])->pluck('total_price')->toArray())
            : [];

        $statisticWidget['customers_name'] = !empty($statisticWidget[$selected_contact_type]['original'])
            ? array_reverse(collect($statisticWidget[$selected_contact_type]['original'])->pluck('customer_name')->toArray())
            : [];

        $statisticWidget['total_by_selected_contact_type'] = !empty($statisticWidget['statistic_widget'][$selected_contact_type])
            ? $statisticWidget['statistic_widget'][$selected_contact_type]
            : 0;

        $this->statistic_widget = $statisticWidget;

        $this->dispatchBrowserEvent('renderStatisticsRadialChart', $this->statistic_widget);
    }

    public function renderStatisticsRadialChart($durationMonths = 1)
    {
        $this->durationMonths = $durationMonths;
        $this->updatedContactType();
    }
}
