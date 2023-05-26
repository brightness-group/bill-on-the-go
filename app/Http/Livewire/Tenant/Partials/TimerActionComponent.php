<?php

namespace App\Http\Livewire\Tenant\Partials;

use App\Models\Tenant\Livetrack;
use Livewire\Component;

class TimerActionComponent extends Component
{
    public $counterStarted;
    public $printView = false;

    protected $listeners = [
        '$refresh',
        'startChronosAction',
    ];

    public function render()
    {
        $liveTrack = Livetrack::where('user_id', auth()->id())->first();

        $this->counterStarted = (!empty($liveTrack));

        return view('livewire.tenant.partials.timer-action-component');
    }

    public function startChronosAction()
    {
        $this->counterStarted = true;
    }

    public function stopChronosAction()
    {
        $this->dispatchBrowserEvent( 'stopChronosTimerAction');
    }
}
