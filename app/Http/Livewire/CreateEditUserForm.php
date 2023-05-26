<?php

namespace App\Http\Livewire;

use App\Helpers\CoreHelpers;
use App\Notifications\Admin\InviteUserMailSendNotify;
use App\Notifications\InviteUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Tenancy\Facades\Tenancy;
use App\Models\User;

class CreateEditUserForm extends Component
{
    public $name, $email, $modelId;
    public $prevName, $prevEmail;

    public $rolesCollection;
    public $roleSelected = ['id' => 2, 'name' => 'User'];
    public $prevRole;

    protected $listeners = [
        'getUserModelId',
        'selectedCompany',
        'forcedCloseUserModal'
    ];

    public function render()
    {
        $this->rolesCollection = Role::pluck('name', 'id');
        return view('livewire.create-edit-user-form', ['roles' => $this->rolesCollection]);
    }

    public function updated($name)
    {
        if ($this->modelId) {
            $this->validateOnly($name, [
                'name' => ['required','min:2',Rule::unique('users')->ignore($this->modelId, 'id')],
                'email' => ['regex:/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,64})$/','required',Rule::unique('users')->ignore($this->modelId, 'id')],
            ]);
        }
        else {
            $this->validateOnly($name, [
                'name' => ['required', 'min:2', 'unique:users'],
                'email' => ['required', 'regex:/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,64})$/', 'unique:users'],
            ]);
        }
    }

    public function selectedRoleItem($value)
    {
        $this->roleSelected = Role::findById($value);
        $this->roleSelected = $this->roleSelected->only('id','name');
    }

    public function getUserModelId($modelId)
    {
        $this->modelId = $modelId;
        $model = User::find($this->modelId);
        $this->name = $model->name;
        $this->email = $model->email;
        $name = $model->getRoleNames();
        $this->roleSelected = !empty($name[0]) ? Role::findByName($name[0])->only('id','name') : ['id' => 2, 'name' => 'User'];
        $this->prevRole = $this->roleSelected;

        $this->prevName = $model->name;
        $this->prevEmail = $model->email;
    }

    public function store()
    {
        if ($this->modelId) {
            $data = [];
            $validatedData = [];
            $roleCheck = '';

            // check if there are any change
            if ($this->name !== $this->prevName) {
                $data = array_merge($data, ['name' => $this->name]);
                $validatedData = array_merge($validatedData, [
                    'name' => ['required', 'min:2', Rule::unique('App\Models\Tenant\User')->ignore($this->modelId, 'id')],
                ]);
            }
            if ($this->email !== $this->prevEmail) {
                $data = array_merge($data, ['email' => $this->email]);
                $validatedData = array_merge($validatedData, [
                    'email' => ['regex:/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,64})$/', 'required', Rule::unique('App\Models\Tenant\User')->ignore($this->modelId, 'id')],
                ]);
            }
            if ($this->roleSelected !== $this->prevRole) {
                $data = array_merge($data, ['roleSelected' => $this->roleSelected]);
                $validatedData = array_merge($validatedData, [
                    'roleSelected' => ['required'],
                ]);
                $roleCheck = $this->roleSelected;
            }
            $validation = Validator::make($data, $validatedData)->validate();
            if (count($validation)) {
                $user = User::find($this->modelId);
                $user->update($validation);
                if (!empty($roleCheck)) {
                    $roleById = Role::findById($roleCheck['id']);
                    $user->syncRoles($roleById);
                    $roleCheck = '';
                }
                $this->emit('showToastrMessageForSystemUsers', 'updated');
            }
        }
        else
        {
            $user = User::create($this->validate([
                'name' => ['required','min:2','unique:users'],
                'email' => ['required','regex:/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,64})$/','unique:users'],
                'roleSelected' => ['required'],
            ]));
            $roleById = Role::findById($this->roleSelected['id']);
            $user->assignRole($roleById);
            $user->notify(new InviteUser(false, null, $user, null, config('app.url')));
            Auth::user()->notify(new InviteUserMailSendNotify($user));
            $this->emit('showToastrMessageForSystemUsers', 'created');
        }
        $this->cleanVars();
        $this->dispatchBrowserEvent('closeFormUserModal');
        $this->emit('refreshParent');
    }

    public function sendResetLink(Request $request)
    {
        $user = User::find($this->modelId);
        $tokenExist = $this->checkTokenExist($user);
        $timeRemain = CoreHelpers::calculateDiffTimes($tokenExist);
        if(is_null($tokenExist) && is_null($timeRemain) || $timeRemain->format('%H:%I') > '00:05') {
            $createdToken = Password::createToken($user);
            $user->sendPasswordResetNotification($createdToken);
            $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.An Email was send for reset password.')]);
        }
        else {
            $this->dispatchBrowserEvent('showToastrError', ['message' => __('locale.An Email was send for reset password.') ." ". __('locale.Please wait :count min before retrying.',['count' => 5-$timeRemain->format('%i')])]);
        }
    }

    public function checkTokenExist($user)
    {
        $tokenExist = DB::table('password_resets')->where('email','=',$user->email)->first();
        return $tokenExist;
    }

    public function closeFormModal()
    {
        $this->forcedCloseUserModal();
        $this->dispatchBrowserEvent('closeFormUserModal');
    }

    public function cleanVars()
    {
        $this->name = '';
        $this->prevName = '';
        $this->email = '';
        $this->prevEmail = '';
        $this->reset('roleSelected');
        $this->prevRole = '';
        $this->modelId = '';
    }

    public function forcedCloseUserModal()
    {
        $this->resetValidation();
        $this->cleanVars();
        $this->emit('clearSelectedItem');
    }
}
