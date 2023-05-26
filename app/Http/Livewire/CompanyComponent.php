<?php

namespace App\Http\Livewire;

use App\Helpers\CoreHelpers;
use App\Models\Company;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Collection;

class CompanyComponent extends Component
{
    use WithPagination;
    protected string $paginationTheme = 'bootstrap';
    public string $search = '';
    public $action, $selectedItem;
    public $companySelected;
    public string $toastrMessage = '';
    public bool $hasMorePage = false;
    public bool $isLoadMore = false;
    public int $bulk = 100;
    public int $loadMoreNumber = 100;
    public $pageNumber;
    protected $listeners = [
        'refreshParent' => '$refresh',
        'searchUpdate',
        'showToastrMessageForCompanyComponent',
        'clearSelectedItem',
        'hasMorePages',
        'statusSwitcher',
        'selectItem'
    ];

    public function mount()
    {
        $this->search = CoreHelpers::getPreviousState('companies-list','search',$this->search);
        $this->bulk = CoreHelpers::getPreviousState('companies-list', 'bulk', $this->bulk);
        $this->pageNumber = $page ?? 1;
        if (session()->has('toastrMessage')) {
            $this->toastrMessage = session()->get('toastrMessage');
            session()->forget('toastrMessage');
        }
    }

    public function hasMorePages($value)
    {
        $this->hasMorePage = $value;
    }

    public function filterBySelectedInputs()
    {
        $connAux = Collection::empty();
        $paginateNumber = (int)$this->bulk <= 1000 ? (int)$this->bulk : (int)$this->loadMoreNumber;
        $connAux = Company::whereLike(['name', 'subdomain', 'address', 'zip', 'city', 'country', 'email', 'contact', 'contact_email'], $this->search ?? '')
            ->paginate($paginateNumber);
        $this->hasMorePages($connAux->hasMorePages());

        return $connAux;
    }

    public function updatingBulk($value)
    {
        CoreHelpers::setState('companies-list', 'bulk', $value);
        if ((int)$value > 1000) {
            $this->isLoadMore = true;
            $this->reset(['loadMoreNumber']);
        } else $this->isLoadMore = false;
    }

    public function render()
    {
        return view('livewire.company-component', [
            'companies' => $this->filterBySelectedInputs()
        ])
        ->extends('theme-new.layouts.layoutMaster', ['title' => "Companies"])
        ->section('content');
    }

    public function searchUpdate($search)
    {
        CoreHelpers::setState('companies-list', 'search', $search);
        $this->search = $search;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function clearSelectedItem()
    {
        $this->selectedItem = '';
    }

    public function selectItem($itemId, $action)
    {
        $this->selectedItem = $itemId;
        $this->action = $action;

        if ($action == 'delete') {
            $company = Company::find($itemId);

            $this->openDeleteModal($company->name);
        }
    }

    public function openDeleteModal($companyName)
    {
        $deleteMsg = __('locale.Delete Company Message', ['companyName' => $companyName]);

        $this->dispatchBrowserEvent('openDeleteModal', ['deleteMsg' => $deleteMsg]);
    }

    public function closeDeleteModal()
    {
        $this->dispatchBrowserEvent('closeDeleteModal');

        $this->selectedItem = '';
    }

    public function deletePhotoOrLogo($itemId)
    {
        $company = Company::find($itemId);

        if ($company->logo) {
            $path_profile = explode('/', $company->logo);
            $builded_path = '/public/' . $path_profile[2] . '/' . $company->id . '/logo/' . $path_profile[5];
            $deleted = Storage::delete($builded_path);
        }
    }

    public function destroy()
    {
        $this->deletePhotoOrLogo($this->selectedItem);

        Company::destroy($this->selectedItem);

        $this->closeDeleteModal();

        $this->dispatchBrowserEvent('showToastrDelete', ['message' => __('locale.Company Deleted!')]);

        $this->search = '';

        $this->emit('hideModal');
    }

    public function showToastrMessageForCompanyComponent()
    {
        if ($this->toastrMessage == 'created')
            $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.Company Created!')]);
        elseif ($this->toastrMessage == 'updated')
            $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.Company Updated!')]);
        $this->reset(['toastrMessage']);
    }

    public function statusSwitcher($id, $action)
    {
        $company = Company::find($id);

        $flag = '';

        if ($action === 'lock') {
            $company->status = 1;
            $flag = 'lock';
        } else {
            $company->status = 0;
            $flag = 'unlock';
        }

        $company->save();

        if ($flag == 'lock') {
            $this->dispatchBrowserEvent('showToastrDelete', ['message' => __('locale.Company Locked!')]);
        } elseif ($flag == 'unlock') {
            $this->dispatchBrowserEvent('showToastrInfo', ['message' => __('locale.Company Unlocked!'), 'text' => '']);
        }

        $this->emit('hideModal');
    }
}
