<?php

namespace App\Http\Livewire;

use App\Helpers\CoreHelpers;
use App\Models\Subdomain;
use Livewire\Component;
use Livewire\WithPagination;

class SystemSubdomainsComponent extends Component
{

    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public string $search = '';

    public $action, $selectedItem;

    protected $listeners = [
    ];

    public function mount()
    {
        $this->search = CoreHelpers::getPreviousState('subdomains-list','search',$this->search);
    }

    public function render()
    {
        return view('livewire.system-subdomains-component', [
            'subdomains' => Subdomain::whereLike(['subdomain', 'description', 'target', 'created_at'], $this->search ?? '')
//            where('name', 'like', '%'.$this->search.'%')
//                ->orWhere('email', 'like', '%'.$this->search.'%')
//                ->orWhere('created_at', 'like', '%'.$this->search.'%')
//                ->orWhere('two_factor_secret', 'like', '%'.$this->search.'%')
                ->paginate(10)
        ])->extends('theme-new.layouts.layoutMaster', ['title' => 'System Subdomains'])
            ->section('content');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function searchUpdate($search)
    {
        CoreHelpers::setState('subdomains-list', 'search', $search);
        $this->search = $search;
    }

    public function clearSelectedItem()
    {
        $this->selectedItem = '';
    }

    public function selectItem($itemId, $action)
    {
        $this->selectedItem = $itemId;
        if ($action == 'delete') {
            $this->dispatchBrowserEvent('openSubdomainDeleteModal');


        } else {
            $this->emit('getSubdomainModelId', $this->selectedItem);
            $this->dispatchBrowserEvent('openFormSubdomainModal');
        }
    }

    public function openFormSubdomainModal()
    {
        $this->dispatchBrowserEvent('openFormSubdomainModal');
    }

    public function closeFormSubdomainModal()
    {
        $this->dispatchBrowserEvent('closeFormSubdomainModal');
    }

    public function closeSubdomainDeleteModal()
    {
        $this->dispatchBrowserEvent('closeSubdomainDeleteModal');
        $this->selectedItem = '';
    }

    public function destroy()
    {
        Subdomain::destroy($this->selectedItem);
        $this->dispatchBrowserEvent('closeSubdomainDeleteModal');
        $this->dispatchBrowserEvent('showToastrDelete', ['message' => __('locale.Subdomain') . " " . __('locale.user Deleted') . "!"]);
        $this->search = '';
    }

    public function showToastrMessageForSystemUsers($flag)
    {
        if ($flag == 'created')
            $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.User Created!')]);
        elseif ($flag == 'updated')
            $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.User Updated!')]);
    }

}
