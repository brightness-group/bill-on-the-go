<?php

namespace App\Http\Livewire\Tenant;

use App\Helpers\CoreHelpers;
use App\Models\Company;
use App\Models\Tenant\Role;
use App\Models\Tenant\SharedUser;
use App\Models\Tenant\SharedUserLink\SharedUserLink;
use App\Models\Tenant\User;
use App\Notifications\Admin\InviteUserMailSendNotify;
use App\Notifications\InviteUser;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Auth\Passwords\TokenRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;
use Livewire\Component;
use Tenancy\Facades\Tenancy;
use function Composer\Autoload\includeFile;

class UserFormComponent extends Component
{

    public $name, $email, $password, $modelId;
    public $prevName, $prevEmail;
    public $password_confirmation;

    public $rolesCollection;
    public $roleSelected = ['id' => 2, 'name' => 'User'];
    public $prevRole;

    public $selectedTXTFileUser, $prevSelectedTXTFileUser, $txtFileUsers;
    public $selectedAPIUsers, $prevSelectedAPIUsers, $apiUsers;

    public $allow_api = false;

    public $changePassword = null;
    public $buttonsHide = false;

    protected $listeners = [
        'getUserModelId',
        'forcedCloseUserModal'
    ];

    public function hydrate()
    {
        $this->emit('initAPIKeysElements');
    }

    public function mount()
    {
        $file = Auth::user()->file()->latest()->first();
        $this->txtFileUsers = $file ? $file->shared_users()->pluck('name','id') : [];
    }

    public function render()
    {
        $this->rolesCollection = Role::pluck('name', 'id');
        $pluckIdOnLink = SharedUserLink::all()->pluck('user_id');
        $true = true;
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

        return view('livewire.tenant.user-form-component', ['roles' => $this->rolesCollection]);
    }

    public function updated($name)
    {
        if ($this->modelId) {
            $this->validateOnly($name, [
                'name' => ['required','min:2',Rule::unique('App\Models\Tenant\User')->ignore($this->modelId, 'id')],
                'email' => ['regex:/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,64})$/','required',Rule::unique('App\Models\Tenant\User')->ignore($this->modelId, 'id')],
                'password' => ['required', 'min:6'],
                'password_confirmation' => ['required', 'min:6']
            ]);
        }
        else {
            $this->validateOnly($name, [
                'name' => ['required','min:2','unique:App\Models\Tenant\User'],
                'email' => ['required','regex:/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,64})$/','unique:App\Models\Tenant\User'],
            ]);
        }
    }

    public function updatedChangePassword()
    {
        if ($this->changePassword == null) {
            if (!empty($this->password))
                $this->password = '';
            if (!empty($this->password_confirmation)) {
                $this->password_confirmation = '';
            }
        }
    }

    public function manageUserApiToken($value)
    {
        $this->allow_api = !$this->allow_api;
    }

    public function getUserModelId($modelId)
    {
        $this->modelId = $modelId;
        $model = User::whereId($this->modelId)->first();

        $this->allow_api = $model->is_allow_api ? true : false;
        if ($sharedLink = SharedUserLink::where('user_id',$model->id)->first()) {
            if ($sharedLink->isTxtFile) {
                $this->selectedTXTFileUser = $sharedLink->shared_user_id;
                $this->prevSelectedTXTFileUser = $this->selectedTXTFileUser;
            } else {
                $this->selectedAPIUsers = $sharedLink->shared_user_id;
                $this->prevSelectedAPIUsers = $this->selectedAPIUsers;
            }
        }

        $this->name = $model->name;
        $this->email = $model->email;
        $name = $model->getRoleNames();
        $this->roleSelected = Role::findByName($name[0])->only('id','name');

        $this->prevRole = $this->roleSelected;
        $this->prevName = $model->name;
        $this->prevEmail = $model->email;
    }

    public function selectedRoleItem($value)
    {
        $this->roleSelected = Role::findById($value);
        $this->roleSelected = $this->roleSelected->only('id','name');
    }

    /**
     * Create|Update User
     *
     * @return void
     */
    public function store()
    {
        // edit user
        if ($this->modelId) {
            $data = [];
            $validatedData = [];
            $roleCheck = '';
            $sharedLink = false;

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
                $data = array_merge($data, [
                    'roleSelected' => $this->roleSelected,
                ]);
                $validatedData = array_merge($validatedData, [
                    'roleSelected' => ['required'],
                ]);
                $roleCheck = $this->roleSelected;
            }
            if ($this->changePassword)
            {
                $data = array_merge($data, [
                    'password' => $this->password,
                    'password_confirmation' => $this->password_confirmation
                ]);

                $validatedData = array_merge($validatedData, [
                    'password' => 'required|min:6',
                    'password_confirmation' => 'min:6|required_with:password|same:password'
                ]);
            }
            if ($this->selectedAPIUsers != $this->prevSelectedAPIUsers || $this->selectedTXTFileUser != $this->prevSelectedTXTFileUser) {
                $sharedLink = true;
            }
            $user = User::find($this->modelId);
            $user->is_allow_api = $user->is_allow_api == true;
            if ($this->allow_api !== $user->is_allow_api) {
                if ($user->tokens()->count() && $user->is_allow_api) {
                    $user->tokens()->delete();
                }
                $data = array_merge($data, ['is_allow_api' => !$user->is_allow_api]);
                $validatedData = array_merge($validatedData, [
                    'is_allow_api' => ['boolean'],
                ]);
            }
            $validation = Validator::make($data, $validatedData)->validate();

            if (count($validation)) {
                $user->update($validation);
                $shared_user = SharedUser::query()->whereKey($this->modelId)->first();
                if ($shared_user && array_key_exists('name', $validation)) {
                    $shared_user->update(['name' => $validation['name']]);
                }
                if ($shared_user && array_key_exists('email', $validation)) {
                    $shared_user->update(['email' => $validation['email']]);
                }
                if (!empty($roleCheck)) {
                    $roleById = Role::findById($roleCheck['id']);
                    $user->syncRoles($roleById);
                    $roleCheck = '';
                }
                $this->emit('showToastrMessageForUser', 'updated');
            }
            if ($sharedLink) {
                if (!$this->selectedAPIUsers && !$this->selectedTXTFileUser)
                    SharedUserLink::query()->where('user_id',$user->id)->delete();
                else
                    SharedUserLink::updateOrCreate(
                        ['user_id' => $user->id],
                        ['shared_user_id' => $this->selectedAPIUsers ?? $this->selectedTXTFileUser, 'isTxtFile' => $this->selectedTXTFileUser ? true : false]
                    );
            }
        }
        else
        {
            // create new user
            $user = User::create($this->validate([
                'name' => ['required','min:2','unique:App\Models\Tenant\User'],
                'email' => ['required','regex:/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,64})$/','unique:App\Models\Tenant\User'],
                'roleSelected' => ['required'],
            ]));
            if ($user) {
                SharedUser::create([
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
            $this->emit('showToastrMessageForUser', 'created');
        }
        $this->closeFormUserModal();
        $this->emit('refreshParent');
        $this->emit('updateProfileComponent');
    }

    public function closeFormUserModal()
    {
        $this->cleanVars();
        $this->dispatchBrowserEvent('closeFormUserModal');
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
        $tokenExist = DB::connection('tenant')->table('password_resets')->where('email','=',$user->email)->first();
        return $tokenExist;
    }

    public function cleanVars()
    {
        $this->name = null;
        $this->prevName = null;
        $this->email = null;
        $this->prevEmail = null;
        $this->reset(['roleSelected']);
        $this->prevRole = null;
        $this->password = null;
        $this->password_confirmation = null;
        $this->changePassword = null;
        $this->modelId = null;
        $this->selectedTXTFileUser = null;
        $this->selectedAPIUsers = null;
        $this->prevSelectedAPIUsers = null;
        $this->prevSelectedTXTFileUser = null;
    }

    public function forcedCloseUserModal()
    {
        $this->resetValidation();
        $this->cleanVars();
        $this->emit('clearSelectedItem');
    }
}
