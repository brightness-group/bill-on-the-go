<?php

namespace App\Http\Livewire\Tenant;

use App\Models\Tenant\ConnectionReport;
use App\Models\Tenant\Tariff;
use App\Services\BorderLineConnectionWatcher;
//use App\Services\TodoApp as TodoAppService;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Tenancy\Facades\Tenancy;

class ConfirmComponent extends Component
{
    public $connection, $arrayOfTariffs = [];
    public $selectedTariff;

    public function mount($connId)
    {
        $this->connection = ConnectionReport::withTrashed()->with('tariff')->where('id',$connId)->first();
        $this->getArrayOfTariffs($this->connection);
        $this->selectedTariff = !empty($this->connection->tariff->id) ? $this->connection->tariff->id : null;
    }

    public function render()
    {
        $tariffsIds = collect($this->arrayOfTariffs)->pluck('id')->toArray();
        $tariffs = Tariff::whereIn('id', $tariffsIds)->where('tariff_state', 'active')->get();
        return view('livewire.tenant.confirm-component', ['tariffs' => $tariffs]);
    }

    public function rules()
    {
        return [
            'selectedTariff' => ['required', 'integer'],
        ];
    }

    public function confirmConnection()
    {
        $validator = Validator::make(
            [
                'selectedTariff' => $this->selectedTariff,
            ]
            , $this->rules())->validate();

        if(count($validator)){
            $this->connection->is_tariff_overlap_confirmed = true;
            $this->connection->tariff_id = $this->selectedTariff;
            $this->connection->save();

            // remove a task from todo list
           /* if (!empty($this->connection->id)) {
                TodoAppService::removeTodo('tariff-overlapping', $this->connection->id);
            }*/

            $this->dispatchBrowserEvent('showToastrSuccess',['message' => __('Connection tariff was confirmed successfully!')]);
            $this->emitTo('tenant.customer-connections-component','refresh');
            $this->dispatchBrowserEvent('refreshConnectionReports');
        }else{
            $this->dispatchBrowserEvent('showToastrError',['message' => __('locale.Something wrong happen with your request.')]);
        }
        $this->closeConfirmModal();
    }

    public function getArrayOfTariffs($connection)
    {
        $initBorderLimitCheck = new BorderLineConnectionWatcher($connection);
        $this->arrayOfTariffs = key_exists('endBorderLimitCross',$array = $initBorderLimitCheck::borderlineEmergence()) ? $array : null;
    }

    public function selectTariff($value)
    {
        $this->selectedTariff = $value;
    }

    public function closeConfirmModal()
    {
        $this->cleanVars();
        $this->emit('hideModal');
    }

    public function cleanVars()
    {
        $this->connection = null;
        $this->selectedTariff = null;
        $this->arrayOfTariffs = [];
        $this->resetValidation();
    }
}
