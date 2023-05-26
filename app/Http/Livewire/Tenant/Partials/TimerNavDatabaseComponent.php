<?php

namespace App\Http\Livewire\Tenant\Partials;

use App\Models\Tenant\Customer;
use App\Models\Tenant\Livetrack;
use App\Services\ConnectionRecoveryService;
use Carbon\Carbon;
use Livewire\Component;

class TimerNavDatabaseComponent extends Component
{
    public $customer;
    public $counter;
    public $isRendered = false;
    public $isStopChronos = false;

    protected $listeners = [
        'startChronos',
        'stopChronoRedirected',
        'stopChronoValue',
        'resumeRecentChronos',
        'renderTimer',
        'refresh' => '$refresh',
    ];

    public function dehydrate()
    {
        if (!$this->isRendered && $this->isStopChronos) {
            $this->isRendered = true;
        }
    }

    public function renderTimer()
    {
        $this->loadData();

        return view('livewire.tenant.partials.timer-nav-database-component');
    }

    public function loadData()
    {
        $liveTrack = Livetrack::where('user_id', auth()->id())->first();

        if (!empty($liveTrack)) {
            $this->counter = true;

            if (!empty($liveTrack->bdgo_id)) {
                $this->customer = Customer::query()->where('bdgogid', $liveTrack->bdgo_id)->first();
            }

            $this->startTimer($liveTrack);
        } else {
            $this->componentUnmount();
        }
    }

    public function startTimer($liveTrack)
    {
        if (!empty($liveTrack)) {
            $now = now()->setTimezone(config('site.default_timezone'));

            $diffSeconds = $now->diffInSeconds(Carbon::createFromFormat('Y-m-d H:i:s', $liveTrack->start_date, config('site.default_timezone'))
                               ->setTimezone(config('site.default_timezone')));

            $hours   = $now->diffInHours($now->copy()->addSeconds($diffSeconds));
            $minutes = $now->diffInMinutes($now->copy()->addSeconds($diffSeconds)->subHours($hours));
            $seconds = $now->diffInSeconds($now->copy()->addSeconds($diffSeconds)->subHours($hours)->subMinutes($minutes));

            $counterTime = str_pad($hours, 2, "0", STR_PAD_LEFT) . ' : ' . str_pad($minutes, 2, "0", STR_PAD_LEFT) . ' : ' . str_pad($seconds, 2, "0", STR_PAD_LEFT);

            // Remove entries from connection recoveries table
            auth()->user()->connection_recovery()->delete();

            // Update stored-time on resume of time track
            $this->dispatchBrowserEvent('updateStoredTime', ['new_time' => $counterTime]);

            $this->dispatchBrowserEvent('start');

            $this->emitTo('tenant.partials.timer-action-component', '$refresh');
        }
    }

    public function resumeRecentChronos()
    {
        $model = auth()->user()->connection_recovery()->first();

        if ($model) {
            $this->startTimer($model);

            $liveTrack = Livetrack::withTrashed()->where('user_id', $model->user_id)->where('id', $model->livetrack_id)->first();

            if (!empty($liveTrack)) {
                $liveTrack->end_date = null;

                $liveTrack->deleted_at = null;

                $liveTrack->save();
            }

            $this->startChronosTimerComponent();

            session()->forget('connection_recovery');
        }
    }

    public function startChronos($bdgo_id=null)
    {
        $startTime = now()->setTimezone(config('site.default_timezone'));

        $liveTrack = Livetrack::where('user_id', auth()->id())->first();

        if (empty($liveTrack->id)) {
            Livetrack::create([
                'user_id'           => auth()->id(),
                'user_name'         => auth()->user()->name,
                'bdgo_id' => !empty($bdgo_id) ? $bdgo_id : null,
                'start_date'        => $startTime,
                'last_poll_date'    => $startTime
            ]);
        }

        $this->emit('refresh');

        $this->startChronosTimerComponent();
    }

    public function startChronosTimerComponent()
    {
        $liveTrack = Livetrack::where('user_id', auth()->id())->first();

        if (!empty($liveTrack)) {
            $this->counter = true;

            $this->loadData();

            $this->dispatchBrowserEvent('DOMContentLoaded');
        }
    }

    public function stopChronoRedirected()
    {
        if (session()->has('stopChronoRedirected') && session()->get('stopChronoRedirected') == true) {
            $this->emitTo('spinner-window-component', 'openSpinnerWindow', __('locale.Please wait...'));

            session()->forget('stopChronoRedirected');
            session()->save();

            $this->isStopChronos = true;

            $this->stopChronos();

            $this->emitTo('spinner-window-component', 'closeSpinnerWindow');
        }
    }

    public function stopChronos()
    {
        $liveTrack = Livetrack::where('user_id', auth()->id())->first();

        if (!empty($liveTrack)) {
            $this->counter = false;

            if (!$this->isRendered) {
                // Sleep 2 seconds wait for render html because this stop listener call this component.
                sleep(2);
            }

            $this->dispatchBrowserEvent('stop');
        }
    }

    public function stopChronoValue()
    {
        $currentTime = now()->setTimezone(config('site.default_timezone'));

        $liveTrack = Livetrack::where('user_id', auth()->id())->first();

        if (!empty($liveTrack)) {
            // Remove from livetracks records
            $connectionRecoveryService = new ConnectionRecoveryService();
            $connectionRecoveryService->removeLivetracksOnStopChronosByUserId(auth()->id());

            $liveTrack->end_date = $currentTime;

            if ($liveTrack->save()) {
                $this->editConnectionChrono($liveTrack);

                $this->counter = false;

                $this->emitTo('tenant.partials.timer-action-component', '$refresh');
            }
        }
    }

    public function editConnectionChrono($liveTrack)
    {
        $data = [
            'counter_start' => $liveTrack->start_date->format('d.m.Y H:i:s'),
            'counter_end' => $liveTrack->end_date->format('d.m.Y H:i:s'),
            'customer' => $this->customer
        ];

        $this->emit('showModal', 'tenant.activity-form-component', 'manual-activity', json_encode($data));
    }

    public function stopChronosTimerComponent()
    {
        if ($this->counter) {
            session(['stopChronoRedirected' => true]);

            return redirect()->route('customer.connections');
        }
    }

    public function componentUnmount()
    {
        $this->counter  = false;
        $this->customer = null;
    }
}
