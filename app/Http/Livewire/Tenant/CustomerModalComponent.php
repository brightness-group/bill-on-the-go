<?php

namespace App\Http\Livewire\Tenant;

use App\Models\Tenant\Customer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Tenancy\Facades\Tenancy;

class CustomerModalComponent extends Component
{
    public $actionType = null;
    public $customer_name;
    public $connectionData;
    public $activityName;
    public $planned_operating_time;
//    protected $listeners = [
//        'refresh' => '$refresh',
//        'shownCustomerModal' => 'openCustomerModal',
//        'cleanVars',
//    ];

    public function mount($activityName, $connectionData = null)
    {
        $this->activityName = $activityName;
        $this->connectionData = $connectionData;
    }

    public function rules()
    {
        return [
            'customer_name' => ['required', 'min:2', Rule::unique('App\Models\Tenant\Customer')],
            'planned_operating_time' => ['nullable'],
        ];
    }

    public function render()
    {
        return view('livewire.tenant.customer-modal-component');
    }

    public function save()
    {
        $validator = Validator::make(
            [
                'customer_name' => $this->customer_name,
                'planned_operating_time' => $this->planned_operating_time,
            ]
            , $this->rules())->validate();
        if (count($validator)) {
            $groupId = $this->getGroupIdGenerated();
            $customer = Customer::create($validator + ['bdgogid' => $groupId]);
            $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.Customer Created!')]);
        }
        if (!empty($customer->id)) {
            $selectedCustomer = Customer::where('bdgogid',$groupId)->first();
            $this->connectionData['selectedCustomer'] = collect($selectedCustomer)->toArray();
            $this->connectionData['bdgogid'] = $groupId;
            $this->connectionData['groupname'] = $selectedCustomer->customer_name;
        }
        $this->closeCustomerModal();
    }

    public function getGroupIdGenerated(): string
    {
        $randomGroupId = $this->generateRandomOwnGroupId();
        if (Customer::query()->where('bdgogid', $randomGroupId)->exists()) {
            $this->getGroupIdGenerated();
        } else {
            return $randomGroupId;
        }
    }

    public function generateRandomOwnGroupId(): string
    {
        return 't' . Tenancy::getTenant()->getTenantKey() . '-g' . strtolower($this->generateRandomString(9));
    }

    public function generateRandomString($length)
    {
        return substr(str_shuffle('123456789'),1,$length);
    }

    public function cleanVars()
    {
        $this->customer_name = null;
        $this->planned_operating_time = null;
        $this->resetValidation();
    }

    public function openCustomerModal($activityName = null)
    {
        $this->dispatchBrowserEvent('openCustomerModal', ['activityName' => $activityName]);
    }

    public function closeCustomerModal()
    {
        $this->cleanVars();
        $item = !empty($this->connectionData['selectedConnection']['id']) ? $this->connectionData['selectedConnection']['id'] : null;
        $this->emit('showModal', 'tenant.activity-form-component', $this->activityName, json_encode(['item' => $item, 'customer' => $this->connectionData['selectedCustomer']]), json_encode($this->connectionData));
    }
}
