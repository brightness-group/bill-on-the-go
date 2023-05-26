<?php

namespace App\Http\Livewire;

use App\Models\Company;
use App\Models\Tenant\User;
use Livewire\Component;
use Livewire\WithPagination;
use Tenancy\Facades\Tenancy;

class CompanyUsersComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $companySelected;

    public $term;

    public $selectedItem;

    public $action;

    const SEARCH_LISTENER = 'updateSearch';

    protected $listeners = [self::SEARCH_LISTENER];

    public function mount(Company $company)
    {
        $this->companySelected = $company;
    }

    public function render()
    {
        Tenancy::setTenant($this->companySelected);

        return view('livewire.company-users-component', [
            'modelsUsers' => User::whereLike(['name', 'email', 'created_at'], $this->term ?? '')->paginate(15)
        ])
        ->extends('theme-new.layouts.layoutMaster')
        ->section('content');
    }

    public function updateSearch($term)
    {
        $this->term = $term;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function selectItem($itemId, $action)
    {
        $this->selectedItem = $itemId;

        $this->action = $action;

        if ($action == 'delete') {
            $this->openDeleteModal();
        } else {
            $this->openUserModal();
        }
    }

    public function openUserModal()
    {
        $this->emit('showModal', 'company-user-modals', $this->companySelected, $this->selectedItem, $this->action);
    }

    public function openDeleteModal()
    {
        $this->emit('showModal', 'company-user-modals', $this->companySelected, $this->selectedItem, $this->action);
    }
}
