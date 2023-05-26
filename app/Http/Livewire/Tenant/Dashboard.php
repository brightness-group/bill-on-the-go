<?php

namespace App\Http\Livewire\Tenant;

use App\Helpers\CoreHelpers;
use App\Helpers\Helper;
use App\Services\DashboardWidgetService;
use Livewire\Component;

class Dashboard extends Component
{
    public int $duration_months = 1;
    public int $turn_over_duration_months = 1;
    public int $statistics_radial_duration_months = 1;
    public string $statistics_radial_contact_type_name = 'tv_customers';
    public array $greeting_widget = [];
    public array $turnover_widget = [];
    public array $statistic_widget = [];
    public array $initial_operating_times_widget = [];
    public array $topCustomers = [];
    public array $operating_times_widget = [];
    public array $revenue_category_widget = [];

    protected $listeners = [
        'computeDashboardWidgetsData',
    ];

    public function mount()
    {
        $this->turn_over_duration_months = CoreHelpers::getPreviousState('dashboard', 'turnoverDurationMonths', $this->turn_over_duration_months);
        $this->duration_months = CoreHelpers::getPreviousState('dashboard', 'overviewOperatingDurationMonths', $this->duration_months);
        $this->statistics_radial_duration_months = CoreHelpers::getPreviousState('dashboard', 'statisticsRadialDurationMonth', $this->statistics_radial_duration_months);
        $this->statistics_radial_contact_type_name =  CoreHelpers::getPreviousState('dashboard', 'statisticsRadialContactTypeName', $this->statistics_radial_contact_type_name);
        $this->reRenderAllCharts();
    }

    public function render()
    {
        $this->getAllWidgetsData();
        return view('livewire.tenant.dashboard')
            ->extends('tenant.theme-new.layouts.layoutMaster')
            ->section('content');
    }

    /**
     * getAllWidgetsData
     *
     * @return void
     */
    public function getAllWidgetsData()
    {
        $dashboardWidgetService = new DashboardWidgetService();
        $dashboardWidget = $dashboardWidgetService->getAllWidgetsData();
        $dashboardWidget = gettype($dashboardWidget) == 'object'? collect($dashboardWidget)->toArray() : $dashboardWidget;
        $this->greeting_widget = $dashboardWidget['greeting_widget'];

        $statisticDurationMonths = $this->statistics_radial_duration_months == 1 ? 'monthly_data' : 'quarterly_data';
//        dd($statisticDurationMonths);
        $statisticWidget = (!empty($dashboardWidget['statistic_widget'][$statisticDurationMonths])) ? $dashboardWidget['statistic_widget'][$statisticDurationMonths] : [];

        $statisticWidget['series_data'] = !empty($statisticWidget[$this->statistics_radial_contact_type_name]['percentage'])
            ? array_reverse($statisticWidget[$this->statistics_radial_contact_type_name]['percentage'])
            : [];

        $statisticWidget['total_price'] = (!empty($statisticWidget[$this->statistics_radial_contact_type_name]['original']))
            ? array_reverse(collect($statisticWidget[$this->statistics_radial_contact_type_name]['original'])->pluck('total_price')->toArray())
            : [];

        $statisticWidget['customers_name'] = !empty($statisticWidget[$this->statistics_radial_contact_type_name]['original'])
            ? array_reverse(collect($statisticWidget[$this->statistics_radial_contact_type_name]['original'])->pluck('customer_name')->toArray())
            : [];

        $statisticWidget['total_by_selected_contact_type'] = !empty($statisticWidget['statistic_widget'][$this->statistics_radial_contact_type_name])
            ? $statisticWidget['statistic_widget'][$this->statistics_radial_contact_type_name]
            : 0;

        $this->statistic_widget = $statisticWidget;

        $this->turnover_widget = $dashboardWidget['turnover_widget'];
        $this->operating_times_widget = $dashboardWidget['operating_times_widget'];
        $this->revenue_category_widget = $dashboardWidget['revenue_category_widget'];
        $this->initial_operating_times_widget = $this->duration_months == 1
            ? $dashboardWidget['operating_times_widget']['monthly_data']
            : $dashboardWidget['operating_times_widget']['quarterly_data'];

        $this->topCustomers = (!empty($dashboardWidget['top_five_customers'])) ? $dashboardWidget['top_five_customers'] : [];
    }

    public function renderStatisticsRadialChart()
    {
        // code here
        $this->dispatchBrowserEvent('renderStatisticsRadialChart');
    }

    public function reRenderAllCharts()
    {
        $this->renderStatisticsRadialChart();
//        $this->dispatchBrowserEvent('renderOverviewOperatingTimesChart');
    }

    public function computeDashboardWidgetsData()
    {
        // refresh dashboard widgets
        Helper::computeAndStoreAllDashboardWidgets();
        $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.Updated')]);
        return redirect()->route('dashboard');
    }
}
