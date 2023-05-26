<?php

namespace App\Http\Livewire\Tenant;

use App\Models\Tenant\ConnectionReport;
use App\Models\Tenant\Customer;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class ConnectionPrintViewComponent extends Component
{
    public $selectedCustomer;
    public $selectedCalendar;
    public $start_date;
    public $end_date;

    public $headers, $data, $charged, $sumChargedDuration, $sumCharged, $notCharged, $sumNotChargedDuration, $sumNotCharged;

    protected $listeners = [
        'loadData'
    ];

    public function mount()
    {
        $this->headers = $this->loadHeaders();
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.tenant.connection-print-view-component');
    }

    public function loadHeaders()
    {
        return [
            'start' => 'Start',
            'end' => 'End',
            'duration' => 'Duration',
            'units' => 'Units',
            'price' => 'Price',
            'devicename' => 'Device',
            'username' => 'User',
            'billing_state' => 'Charge',
            'tariff' => 'Tariff',
            'notes' => 'Job Description',
        ];
    }

    public function filterByStartDate($value)
    {
        $start_time = new \DateTime($value);
        $start_time = $start_time->format('d.m.Y 00:00');
        return date_create($start_time);
    }

    public function filterByEndDate($value)
    {
        $end_time = new \DateTime($value);
        $end_time = $end_time->format('d.m.Y 23:59');
        return date_create($end_time);
    }

    public function loadData()
    {
//        if (session()->has('printViewData')) {
//            $this->data = session()->get('printViewData');
//            session()->forget('printViewData');
//            $chargedItems = $this->data['charged'];
//            $this->charged = $chargedItems->map(function ($item) {
//                return ConnectionReport::where('id',$item)->first();
//            });
//            $this->sumChargedDuration = $this->data['sumChargedDuration'];
//            $this->sumCharged = $this->data['sumCharged'];
//
//            $noChargedItems = $this->data['notCharged'];
//            $this->notCharged = $noChargedItems->map(function ($item) {
//                return ConnectionReport::where('id',$item)->first();
//            });
//            $this->sumNotChargedDuration = $this->data['sumNotChargedDuration'];
//            $this->sumNotCharged = $this->data['sumNotCharged'];
//        }
        $connections = ConnectionReport::
                                    group($this->selectedCustomer)
                                    ->where('start_date','>=',$this->filterByStartDate($this->start_date))
                                    ->where('end_date','<=',$this->filterByEndDate($this->end_date))
                                    ->whereNotNull('tariff_id')
                                    ->orderBy('start_date','desc')->get();
        $this->charged = new Collection();
        $this->notCharged = new Collection();
        $connections->map(function ($connection) {
            if ($connection->billing_state == 'Bill') {
                $this->charged->push($connection);
            }
            elseif ($connection->billing_state == 'DoNotBill') {
                $this->notCharged->push($connection);
            }
        });
        $this->sumChargedDuration = $this->calculatePDFDuration($this->charged);
        $this->sumCharged = $this->calculatePDFIncome($this->charged);
        $this->sumNotChargedDuration = $this->calculatePDFDuration($this->notCharged);
        $this->sumNotCharged = $this->calculatePDFIncome($this->notCharged);
    }

    public function calculatePDFDuration($items)
    {
        $sum = 0;
        foreach ($items as $item)
            $sum += $item->duration();
        return $sum;
    }

    public function calculatePDFIncome($items)
    {
        $sum = 0;
        foreach ($items as $item)
            $sum += $item->calculatePrice();
        return number_format($sum,2,',','.');
    }

    public function changeBillColumn($item)
    {
        $model = ConnectionReport::find($item);
        if ($model) {
            if ($model->billing_state == 'Bill')
                $model->billing_state = 'DoNotBill';
            else $model->billing_state = 'Bill';
            $model->save();
            // ----
            $this->emitUp('printViewGeneratorEvent');
        }
    }
}
