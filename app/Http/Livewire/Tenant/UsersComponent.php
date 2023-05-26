<?php

namespace App\Http\Livewire\Tenant;

use App\Models\Tenant\SharedUser;
use App\Models\Tenant\User;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use Tenancy\Facades\Tenancy;

class UsersComponent extends Component
{

    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';

    public $action, $selectedItem;

    public $flashMessage;

    public $isTenantInfoComponent;

    protected $listeners = [
        'refreshParent' => '$refresh',
        'searchUpdate',
        'showToastrMessageForUser',
        'clearSelectedItem'
    ];

    public function mount()
    {
        $pluckIds = User::all()->pluck('id');
        $shared = SharedUser::query()->whereIn('id',$pluckIds)->get();
        if (count($shared)) {
            $shared->flatMap(function ($user) {
                $user->update(['isTv' => false]);
            });
        }

        if (session()->has('redirectUsersTab')) {
            session()->forget('redirectUsersTab');
        }
        $this->isTenantInfoComponent = false;
    }

    public function render()
    {
        if (Request::is('*/tenant.show-tenant-info')) {
            $this->isTenantInfoComponent = true;
        } else {
            $this->isTenantInfoComponent = false;
        }
        return view('livewire.tenant.users-component', [
            'users' => User::where(function ($query) {
                                if ($this->search)
                                    $query->whereLike(['name','email','created_at','two_factor_secret'],$this->search ?? '');
                            })
//            where('name', 'like', '%'.$this->search.'%')
//                            ->orWhere('email', 'like', '%'.$this->search.'%')
//                            ->orWhere('created_at', 'like', '%'.$this->search.'%')
//                            ->orWhere('two_factor_secret', 'like', '%'.$this->search.'%')
                            ->paginate(10)
        ])
            ->extends('tenant.layouts.contentLayoutMaster')
            ->section('content');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function searchUpdate($search)
    {
        $this->search = $search;
    }

    public function selectItem($itemId, $action)
    {
        $this->selectedItem = $itemId;

        if ($action == 'delete') {
            if ( $this->attemptedUserDeletion($this->selectedItem)) {
                $this->dispatchBrowserEvent('openUserDeleteModalId', ['userId' => $this->selectedItem]);
            }
            else {
                $this->showToastrMessageForUser('no delete');
                $this->clearSelectedItem();
            }
        }
        else {
            $this->emit('getUserModelId', $this->selectedItem);
            $this->dispatchBrowserEvent('openFormUserModal');
        }
    }

    public function clearSelectedItem()
    {
        $this->selectedItem = null;
    }

    public function attemptedUserDeletion($id)
    {
        $count = $this->checkForLastAdminUser();
        $user = User::find($id);
        if ($user == auth()->user() || ($count == 1 && $user->hasRole('Admin'))) {
            return false;
        }
        else return true;
    }

    public function checkForLastAdminUser()
    {
        $count = 0;
        $users = User::all();
        foreach ($users as $user) {
            if ($user->hasRole('Admin'))
                $count++;
        }
        return $count;
    }

    public function toggleApiAccess($userId)
    {
        $user = User::whereId($userId)->first();
        if($user->tokens()->count()){
            $user->tokens()->delete();
        }
        $user->update(['is_allow_api' => !$user->is_allow_api]);
        $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.User Updated!')]);
        $this->emit('refreshParent');
    }

    public function toggleLock($userId)
    {
        $user = User::whereId($userId)->first();
        if($user->tokens()->count()){
            $user->tokens()->delete();
        }
        $user->update(['is_allow_api' => !$user->is_allow_api]);
        $this->dispatchBrowserEvent('showToastrSuccess', ['message' => $user->is_allow_api ? __('locale.User Unlocked!') : __('locale.User Locked!')]);
        $this->emit('refreshParent');
    }

    public function closeFormModal()
    {
        $this->emit('forcedCloseUserModal');
        $this->dispatchBrowserEvent('closeFormUserModal');
    }

    public function closeDeleteModal()
    {
        $this->dispatchBrowserEvent('closeUserDeleteModalId', ['userId' => $this->selectedItem]);
        $this->clearSelectedItem();
    }

    public function deletePhoto($itemId)
    {
        $user = User::find($itemId);
        if ($user->profile_photo_path) {
            $path_profile = explode('/', $user->profile_photo_path);
            $builded_path = '/public/'.$path_profile[2].'/'.Tenancy::getTenant()->getTenantKey().'/users/photo/'.$path_profile[6];
            $deleted = Storage::delete($builded_path);
        }
    }

    public function destroy()
    {
        $this->deletePhoto($this->selectedItem);
        $user = User::find($this->selectedItem);
        $user->notifications()->delete();
        User::destroy($this->selectedItem);
        SharedUser::query()->whereKey($this->selectedItem)->update(['active' => false]);
        $this->closeDeleteModal();
        $this->showToastrMessageForUser('deleted');
        $this->search = '';
    }

    public function showToastrMessageForUser($flag)
    {
        $this->flashMessage = $flag;
        if ($this->flashMessage == 'created') {
            $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.User Created!')]);
        }
        elseif ($this->flashMessage == 'updated') {
            $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.User Updated!')]);
        }
        elseif ($this->flashMessage == 'deleted') {
            $this->dispatchBrowserEvent('showToastrDelete', ['message' => __('locale.User Deleted!')]);
        }
        elseif ($this->flashMessage == 'no delete') {
            $this->dispatchBrowserEvent('showToastrError', ['message' => __('locale.Last "Admin" user or user session opened can\'t be deleted')]);
        }
        $this->flashMessage = '';
    }

    public function openUserModal()
    {
        $this->dispatchBrowserEvent('openFormUserModal');
    }
}
