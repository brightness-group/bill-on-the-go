<?php

namespace App\Http\Livewire\Tenant\bdgo;

use App\Models\Bdgo\DpaCategory;
use App\Models\Bdgo\DpaCompany;
use Livewire\Component;

class AddProviderComponent extends Component
{
    public $dpaCategories, $dpaCompanies;

    public function mount()
    {
        $this->dpaCategories = DpaCategory::customerType()->get();

        $this->dpaCompanies  = DpaCompany::customerType()->get();
    }

    public function render()
    {
        return view('livewire.tenant.bdgo.add-provider-component');
    }

    protected function rules()
    {
        return [];
    }

    public function saveProvider()
    {
        $validatedData = $this->validate();
    }
}
