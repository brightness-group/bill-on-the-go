<?php

namespace App\Http\Livewire;

use App\Helpers\CoreHelpers;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class SystemUsersComponent extends Component
{

    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public string $search = '';

    public $action, $selectedItem;

    protected $listeners = [
        'refreshParent' => '$refresh',
        'searchUpdate',
        'showToastrMessageForSystemUsers',
        'clearSelectedItem',
        'closeChangePasswordUserModal'
    ];

    public function mount()
    {
        $this->search = CoreHelpers::getPreviousState('users-list', 'search', $this->search);
    }

    public function render()
    {
        return view('livewire.system-users-component', [
            'users' => User::whereLike(['name', 'email', 'created_at', 'two_factor_secret'], $this->search ?? '')
//            where('name', 'like', '%'.$this->search.'%')
//                ->orWhere('email', 'like', '%'.$this->search.'%')
//                ->orWhere('created_at', 'like', '%'.$this->search.'%')
//                ->orWhere('two_factor_secret', 'like', '%'.$this->search.'%')
                ->paginate(5)
        ])->extends('theme-new.layouts.layoutMaster', ['title' => 'System Users'])
            ->section('content');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function searchUpdate($search)
    {
        CoreHelpers::setState('users-list', 'search', $search);
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
            if ($this->attemptedUserDeletion($this->selectedItem)) {
                $this->dispatchBrowserEvent('openUserDeleteModal');
            } else {
                $this->dispatchBrowserEvent('showToastrError', ['message' => __('locale.Last "Admin" user or user session opened can\'t be deleted')]);
                $this->clearSelectedItem();
            }
        } elseif ($action == 'update') {
            $this->emit('getUserModelId', $this->selectedItem);
            $this->dispatchBrowserEvent('openFormUserModal');
        } elseif ($action == 'change-password') {
            $this->emit('getUserModelId', $this->selectedItem);
            $this->dispatchBrowserEvent('openChangePasswordUserModal');
        }
    }

    public function closeChangePasswordUserModal()
    {
        $this->dispatchBrowserEvent('closeChangePasswordUserModal');
    }

    public function attemptedUserDeletion($id): bool
    {
        $count = count($this->checkForLastAdminUser());
        $user = User::find($id);
        if ($user == auth()->user() || $count == 1 && $user->hasRole('Admin')) {
            return false;
        } else
            return true;
    }

    public function checkForLastAdminUser()
    {
        return User::role('Admin')->get();
    }

    public function closeUserDeleteModal()
    {
        $this->dispatchBrowserEvent('closeUserDeleteModal');
        $this->selectedItem = '';
    }

    public function deletePhoto($itemId)
    {
        $user = User::find($itemId);
        if (!empty($user->profile_photo_path)) {
            $path_profile = explode('/', $user->profile_photo_path);
            $builded_path = '/public/' . $path_profile[2] . '/' . $path_profile[3] . '/' . $path_profile[4];
            Storage::delete($builded_path);
        }
    }

    public function destroy()
    {
        $this->deletePhoto($this->selectedItem);
        User::destroy($this->selectedItem);
        $this->dispatchBrowserEvent('closeUserDeleteModal');
        $this->dispatchBrowserEvent('showToastrDelete', ['message' => __('locale.User Deleted!')]);
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
