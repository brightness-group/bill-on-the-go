<?php

namespace App\Http\Livewire\Tenant;

use App\Models\Tenant\Customer;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerSelectComponent extends Component
{
    use WithPagination;
    public $search = '';

    protected $listeners = [
        'closeSearchPanel',
        'customerSelected',
        'customerShow',
    ];

    public function render()
    {
         $customers = Customer::where('customer_name','like','%'.$this->search.'%')
            ->orWhere('email','like','%'.$this->search.'%')
            ->orWhere('address','like','%'.$this->search.'%')
            ->orWhere('phone','like','%'.$this->search.'%')
            ->paginate(10);

        return view('livewire.tenant.customer-select-component',['customers'=>$customers])
            ->extends('tenant.layouts.contentLayoutMaster')
            ->section('content');
    }

    public function updatedSearch()
    {
        if ($this->search) {
            $this->dispatchBrowserEvent('showCollect');
        }
        else {
            $this->customers = [];
            $this->dispatchBrowserEvent('hideCollect');
        }
    }

    public function closeSearchPanel()
    {
        $this->search = '';
        $this->dispatchBrowserEvent('hideCollect');
    }
}
