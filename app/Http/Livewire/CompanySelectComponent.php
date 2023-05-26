<?php

namespace App\Http\Livewire;

use App\Models\Company;
use Livewire\Component;
use Livewire\WithPagination;

class CompanySelectComponent extends Component
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
        $companies = Company::where('name','like','%'.$this->search.'%')
            ->orWhere('subdomain','like','%'.$this->search.'%')
            ->orWhere('created_at','like','%'.$this->search.'%')
            ->paginate(10);

        return view('livewire.company-select-component',['companies'=>$companies])
            ->extends('layouts.contentLayoutMaster')
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
