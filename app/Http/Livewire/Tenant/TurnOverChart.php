<?php

namespace App\Http\Livewire\Tenant;

use App\Helpers\CoreHelpers;
use Livewire\Component;

class TurnOverChart extends Component
{
    public array $turnover_widget = [
        'months_inputs' => [],
        'more_turnover' => 0,
        'turnover_average' => 0,
        'with_interval_inputs' => [],
        'without_interval_inputs' => []
    ];

    public array $turnoverWidgetData = [];

    public int $durationMonths = 1;

    public function mount($turnover_widget)
    {
        $this->turnover_widget = $turnover_widget;

        $this->durationMonths = CoreHelpers::getPreviousState('dashboard', 'turnoverDurationMonths', $this->durationMonths);

        $this->updateTimeDuration($this->durationMonths);
    }

    public function render()
    {
        return view('livewire.tenant.turn-over-chart');
    }

    public function updateTimeDuration($durationMonths = 1)
    {
        CoreHelpers::setState('dashboard', 'turnoverDurationMonths', $durationMonths);

        $this->durationMonths = $durationMonths;

        $this->turnoverWidgetData = !empty($this->turnover_widget['monthly_data']) ? $this->turnover_widget['monthly_data'] : [];

        if ($this->durationMonths == 12) {
            $this->turnoverWidgetData = !empty($this->turnover_widget['quarterly_data']) ? $this->turnover_widget['quarterly_data'] : [];
        }
    }

    public function renderTurnoverChart($durationMonths)
    {
        $this->updateTimeDuration($durationMonths);

        $this->dispatchBrowserEvent('renderTurnoverChart', $this->turnoverWidgetData);
    }
}
