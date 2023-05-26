<?php

namespace App\Http\Livewire;

use App\Models\Tenant\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;
use Livewire\Component;
use Tenancy\Facades\Tenancy;

class UserNameEmailProfileComponent extends Component
{

    use WithFileUploads;

    public $userModel;
    public $name;
    public $email;

    public $prevName;
    public $prevEmail;

    public $profile_photo;
    public $prevProfilePhoto;

    protected $rules = [
        'name' => 'required|min:2|unique:users',
        'email' => 'required|unique:users|regex:/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,64})$/',
        'profile_photo' => ['image','max:1024']   //mimes:jpg,png,jpeg
    ];

    public function mount()
    {
        $this->getModel();
        $this->emitUp('resetPasswordComponent');
    }

    public function render()
    {
        return view('livewire.user-name-email-profile-component');
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'name' => ['required','min:2',Rule::unique('users')->ignore(auth()->user()->id, 'id')],
            'email' => ['regex:/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,64})$/','required',Rule::unique('users')->ignore(auth()->user()->id, 'id')],
            'profile_photo' => ['image', 'max:1024']  //mimes:jpg,png,jpeg
        ]);
    }

    public function getModel()
    {
        $this->userModel = auth()->user();

        $this->varsLoaded($this->userModel);
        if (!empty($this->userModel->profile_photo_path)) {
            $this->prevProfilePhoto = $this->userModel->profile_photo_path;
        }
    }

    public function varsLoaded($user)
    {
        $this->name = $user->name;
        $this->email = $user->email;
        $this->prevName = $user->name;
        $this->prevEmail = $user->email;
    }

    public function save()
    {
        $flag = '';
        $data = [];
        $validatedData = [];

        if (!empty($this->profile_photo)) {
            $validated = $this->validate([
                'profile_photo' => ['image', 'max:1024']   //mimes:jpg,png,jpeg
            ]);
            if ($this->prevProfilePhoto) {
                $this->deletePhoto();
            }
            if (Tenancy::identifyTenant()) {
                $photo = $this->profile_photo->store('/users/photo', 'tenant');
                $path = 'tenants/'.Tenancy::getTenant()->getTenantKey().'/'.$photo;
                $url = Storage::url($path);
                $this->userModel->profile_photo_path = $url;
                $this->userModel->save();
                $this->emit('updateNavbarPhoto');
                $this->prevProfilePhoto = $this->userModel->profile_photo_path;
                $this->profile_photo = '';
                $flag = 'photo updated';
            }
            else {
                $photo = $this->profile_photo->store('/public/usersSystem/photo');
                $url = Storage::url($photo);
                $this->userModel->profile_photo_path = $url;
                $this->userModel->save();
                $this->emit('updateNavbarPhoto');
                $this->prevProfilePhoto = $this->userModel->profile_photo_path;
                $this->profile_photo = '';
                $flag = 'photo updated';
            }
            if (!empty(auth()->user()->profile_photo_path)) {
                $this->prevProfilePhoto = $this->userModel->profile_photo_path;
            }
        }
        if ($this->name !== $this->prevName) {
            $data = array_merge($data, ['name' => $this->name]);
            $validatedData = array_merge($validatedData, [
                'name' => ['required','min:2',Rule::unique('users')->ignore($this->userModel->id, 'id')]
            ]);
            $flag = 'profile updated';
        }
        if ($this->email !== $this->prevEmail) {
            $data = array_merge($data, ['email' => $this->email]);
            $validatedData = array_merge($validatedData, [
                'email' => ['regex:/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,64})$/','required',Rule::unique('users')->ignore($this->userModel->id, 'id')]
            ]);
            $flag = 'profile updated';
        }
        $validation = Validator::make($data, $validatedData)->validate();
        if (count($validation)) {
            $this->userModel->update($validation);
            $this->varsLoaded($this->userModel);
            $this->emit('updateProfileComponent');
        }
        if ($flag == 'photo updated') {
            $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.Profile Photo Updated!')]);
        } elseif ($flag == 'profile updated') {
            $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.User Profile Updated!')]);
        }
        $flag = '';
    }

    public function onResetButton()
    {
        if (!empty($this->prevProfilePhoto)) {
            if (!empty($this->profile_photo)) {
                $this->profile_photo = '';
            }
            else {
                $this->dispatchBrowserEvent('openDeletePhotoPathModal');
            }
        }
        else {
            $this->profile_photo = '';
        }
        $this->resetValidation();
    }

    public function deletePhoto()
    {
        if (Tenancy::getTenant()) {
            $path_profile = explode('/', $this->prevProfilePhoto);
            $builded_path = '/public/'.$path_profile[2].'/'.Tenancy::getTenant()->getTenantKey().'/users/photo/'.$path_profile[6];
            $deleted = Storage::delete($builded_path);
            $this->userModel->profile_photo_path = '';
            $this->userModel->save();
        }
        else {
            $path_profile = explode('/', $this->prevProfilePhoto);
            $builded_path = '/public/'.$path_profile[2].'/'.$path_profile[3].'/'.$path_profile[4];
            $deleted = Storage::delete($builded_path);
            $this->userModel->profile_photo_path = '';
            $this->userModel->save();
        }
    }

    public function deletePhotoAndActions()
    {
        $this->deletePhoto();
        $this->cleanPhotoVars();
        $this->dispatchBrowserEvent('closeDeletePhotoPathModal');
        $this->emit('updateNavbarPhoto');
        $this->dispatchBrowserEvent('showToastrDelete', ['message' => __('locale.Profile Photo Deleted!')]);
    }

    public function cleanPhotoVars()
    {
        $this->profile_photo = '';
        $this->prevProfilePhoto = '';
    }

    public function closeDeletePhotoModal()
    {
        $this->dispatchBrowserEvent('closeDeletePhotoPathModal');
    }
}
