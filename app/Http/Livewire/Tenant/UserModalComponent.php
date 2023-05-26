<?php

namespace App\Http\Livewire\Tenant;

use App\Models\Company;
use App\Models\Tenant\Customer;
use App\Models\Tenant\Role;
use App\Models\Tenant\SharedUser;
use App\Models\Tenant\SharedUserLink\SharedUserLink;
use App\Models\Tenant\User;
use App\Notifications\Admin\InviteUserMailSendNotify;
use App\Notifications\InviteUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Tenancy\Facades\Tenancy;

class UserModalComponent extends Component
{
    public $connectionData;
    public $activityName;
    public $user_name, $email, $modelId;

    public $rolesCollection;
    public $roleSelected = ['id' => 2, 'name' => 'User'];

    public $selectedTXTFileUser, $txtFileUsers;
    public $selectedAPIUsers, $apiUsers = [];

//    protected $listeners = [];

    public function mount($activityName, $connectionData = null)
    {
        $file = Auth::user()->file()->latest()->first();
        $this->txtFileUsers = $file ? $file->shared_users()->pluck('name','id') : [];

        $this->activityName = $activityName;
        $this->connectionData = $connectionData;
        $this->selectedCustomer = !empty($connectionData['selectedCustomer']['bdgogid'])
            ? Customer::where('bdgogid', $connectionData['selectedCustomer']['bdgogid'])->first() : null;
    }

    public function render()
    {
        $this->rolesCollection = Role::pluck('name', 'id');
        $pluckIdOnLink = SharedUserLink::all()->pluck('user_id');

        $this->apiUsers = $this->modelId ?
            SharedUser::where(function($query) use ($pluckIdOnLink) {
                $query->active(true)
                    ->where('isTv',true)
                    ->whereNotIn('id',$pluckIdOnLink)
                    ->whereNotIn('id',[$this->modelId]);
            })->pluck('name','id')
            :
            SharedUser::where(function ($query) use ($pluckIdOnLink) {
                $query->active(true)
                    ->where('isTv',true)
                    ->whereNotIn('id',$pluckIdOnLink);
            })->pluck('name','id');

        return view('livewire.tenant.user-modal-component', ['roles' => $this->rolesCollection]);
    }

    public function selectedRoleItem($value)
    {
        $this->roleSelected = Role::findById($value);
        $this->roleSelected = $this->roleSelected->only('id','name');
    }

    public function rules()
    {
        return [
            'name' => ['required', 'min:2', 'unique:App\Models\Tenant\User'],
            'email' => ['required', 'regex:/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,64})$/', 'unique:App\Models\Tenant\User'],
            'roleSelected' => ['required'],
        ];
    }

    public function save()
    {
        $validator = Validator::make(
            [
                'name' => $this->user_name,
                'email' => $this->email,
                'roleSelected' => $this->roleSelected,
            ]
            , $this->rules())->validate();
        $user = User::create($validator);
        if ($user) {
           $sharedUser = SharedUser::create([
                'id' => (string)$user->id,
                'name' => $user->name,
                'email' => $user->email
            ]);
            if ($this->selectedAPIUsers || $this->selectedTXTFileUser) {
                SharedUserLink::create([
                    'user_id' => $user->id,
                    'shared_user_id' => $this->selectedAPIUsers ?? $this->selectedTXTFileUser,
                    'isTxtFile' => $this->selectedTXTFileUser ? true : false
                ]);
            }
        }
        $roleById = Role::findById($this->roleSelected['id']);
        $user->assignRole($roleById);

        $company = Company::where('id',Tenancy::getTenant()->getTenantKey())->first();

        $user->notify((new InviteUser(true, $company, $user, Auth::user()->name, config('app.asset_url')))->locale(session('locale')));
        Auth::user()->notify(new InviteUserMailSendNotify($user));

        $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.User Created!')]);

        if (!empty($sharedUser->id) || !empty($user->id)) {
            $this->connectionData['userid'] = !empty($sharedUser->id) ? $sharedUser->id : $user->id;
            $this->connectionData['username'] = !empty($sharedUser->name) ? $sharedUser->name : $user->name;
        }
        $this->closeUserModal();
    }

    public function cleanVars()
    {
        $this->name = null;
        $this->email = null;
        $this->reset(['roleSelected']);
        $this->modelId = null;
        $this->selectedTXTFileUser = null;
        $this->selectedAPIUsers = null;
    }

    public function closeUserModal()
    {
        $this->cleanVars();
        $item = !empty($this->connectionData['selectedConnection']['id']) ? $this->connectionData['selectedConnection']['id'] : null;
        $this->emit('showModal', 'tenant.activity-form-component', $this->activityName, json_encode(['item' => $item, 'customer' => $this->connectionData['selectedCustomer']]), json_encode($this->connectionData));
    }
}
