<?php

namespace App\Http\Livewire;

use App\Models\Company;
use Livewire\Component;

class ShowCompanyModals extends Component
{
    public ?Company $company = null;

    public function mount(Company $company = null)
    {
        if (!empty($company) && !empty($company->id)) {
            $this->company = $company;
        }
    }

    public function render()
    {
        return view('livewire.show-company-modals');
    }

    public function toggleShowCompanyComponentModal($isDeletePhoto = false)
    {
        if (!empty($this->company)) {
            $this->emit('showModal', 'show-company-component', $this->company, $isDeletePhoto);
        } else {
            $this->emit('showModal', 'show-company-component');
        }
    }

    public function deletePhoto()
    {
        $this->toggleShowCompanyComponentModal(true);
    }
}
