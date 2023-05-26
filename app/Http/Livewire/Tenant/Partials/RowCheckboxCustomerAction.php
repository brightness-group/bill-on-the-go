<?php

namespace App\Http\Livewire\Tenant\Partials;

use App\Models\Tenant\Customer;
use Livewire\Component;

class RowCheckboxCustomerAction extends Component
{
    public Customer $customer;

    public function render()
    {
        return view('livewire.tenant.partials.row-checkbox-customer-action');
    }

    public function changeCustomerStatus()
    {
        if ($this->customer->trashed()) {
            $this->customer->restore();
            $this->dispatchBrowserEvent('showToastrSuccess',['message' => __('locale.Customer Activated!')]);
        }
        else {
            $this->customer->delete();
            $this->dispatchBrowserEvent('showToastrSuccess',['message' => __('locale.Customer Archieved!')]);
        }
        $this->emitTo('tenant.customer-component','refreshParent');
    }
}
