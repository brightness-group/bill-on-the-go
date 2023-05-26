<?php

namespace App\Http\Livewire\Tenant;

use App\Helpers\Helper;
use Livewire\Component;
use Tenancy\Facades\Tenancy;

class GreetingContent extends Component
{
    public $monthly_revenue;
    public $monthly_revenue_percentage;

    public $tenant;

    public $pendingJobs = 0;
    public $batchProgress = 0;

    protected $listeners = [
        'checkRunningBatch'
    ];

    public function mount($monthly_revenue = 0, $monthly_revenue_percentage = 0)
    {
        $this->tenant = Tenancy::getTenant();

        $this->checkRunningBatch();

        $this->monthly_revenue = $monthly_revenue;
        $this->monthly_revenue_percentage = $monthly_revenue_percentage;
    }

    public function render()
    {
        return view('livewire.tenant.greeting-content');
    }

    public function computeAllWidgets()
    {
        $this->emit('computeDashboardWidgetsData');
    }

    public function checkRunningBatch()
    {
        if (!empty($this->tenant->batch_id)) {
            $batch = Helper::checkDashboardRunningBatch($this->tenant->batch_id);

            if (!empty($batch)) {
                $this->pendingJobs = $batch->pendingJobs;
                $this->batchProgress = $batch->progress();

                if ($this->pendingJobs > 0) {
                    $this->dispatchBrowserEvent('showBatchProgress');
                } else {
                    $this->dispatchBrowserEvent('hideBatchProgress');
                }
            }
        }
    }
}
