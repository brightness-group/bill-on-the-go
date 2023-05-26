<?php

namespace App\Http\Livewire\Tenant;

use App\Helpers\Helper;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class EditableComponent extends Component
{
    public $counter = 0;
    public $model_name = '';
    public $model_id = null;
    public $input_type = 'text';
    public $inputOptions = [];
    public $selected_field_name = null;
    public $selected_field_value = '';
    public $prev_model_id = null;
    public $prev_selected_field_value = '';
    public $key = null;

    public $listeners = [
        'setSelectedValue',
    ];

    public function mount($id)
    {
        $this->model_id = $id;
        $this->listeners = array_merge($this->listeners, ["computeAndStoreAllDashboardWidgets" . $this->model_id => "computeAndStoreAllDashboardWidgets"]);
    }

    public function render()
    {
        return view('livewire.tenant.editable-component');
    }

    public function showEditableInput($name, $key, $value)
    {
        $this->emitTo('tenant.customer-component', 'showEditableField', $name, $key, $value);
    }

    public function setSelectedValue($name, $key, $value)
    {
        if ($this->model_id == $key) {
            $this->selected_field_name = $name;
            $this->selected_field_value = $value ? $value : '00:00';
            $this->dispatchBrowserEvent('autoFocusInput', ['model_id' => $this->model_id]);
        }
    }

    public function maskPlannedOperatingTime()
    {
        /* $this->emit('initPlannedOperatingTimeMasks');

        $this->dispatchBrowserEvent('autoFocusInput', ['model_id' => $this->model_id]); */
    }

    public function rules(): array
    {
        return [
            'planned_operating_time' => ['required'],
        ];
    }

    public function validateData(): array
    {
        return [
            'planned_operating_time' => $this->selected_field_value,
        ];
    }

    public function updatedSelectedFieldValue()
    {
        $this->submit(true);
    }

    public function submit($updated = NULL)
    {
        $validation = Validator::make($this->validateData(), $this->rules());

        if ($validation->fails()) {
            $error = $validation->getMessageBag();
            $this->dispatchBrowserEvent('focusErrorInput', ['field' => array_key_first($error->getMessages()), 'model_id' => $this->model_id]);
            $validation->validate();
        } else {
            $model = '\\App\\Models\\Tenant\\'.$this->model_name;
            if($this->selected_field_name == 'planned_operating_time' && $this->model_name == 'Customer'){
                if (!str_contains($this->selected_field_value, ':') && !str_contains($this->selected_field_value, ',')) {
                    if(str_contains($this->selected_field_value,'.')){
                        [$hours,$minutes] = explode('.',$this->selected_field_value);
                        if($minutes > 59){
                            $hours = floor($minutes / 60) + $hours;
                            $minutes = ($minutes % 60);
                        }
                        $this->selected_field_value = ($hours * 60 ) + $minutes;
                    }
                    $this->selected_field_value = !empty($this->selected_field_value) ? \App\Helpers\Helper::formatHoursAndMinutes($this->selected_field_value,'%02d:%02d',true)
                    : 0;
                }
                $this->dispatchBrowserEvent('computeAndStoreAllDashboardWidgets',$this->model_id);
            }
            $find = $model::find($this->model_id);

            if (!empty($find)) {
                $find->update([$this->selected_field_name => $this->selected_field_value]);
            }

            $this->dispatchBrowserEvent('autoFocusOutInput', ['model_id' => $this->model_id]);

            $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.Customer Updated!')]);
            if($updated == NULL || $updated == false){
                $this->emitTo('tenant.customer-component', 'showEditableField', '', '', '');
            }
        }
    }

    public function computeAndStoreAllDashboardWidgets()
    {
        Helper::computeAndStoreAllDashboardWidgets();
    }
}

