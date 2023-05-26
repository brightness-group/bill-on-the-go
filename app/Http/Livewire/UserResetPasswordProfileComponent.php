<?php

namespace App\Http\Livewire;

use App\Models\Tenant\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class UserResetPasswordProfileComponent extends Component
{

    public $userId;
    public $password = '';
    public $password_confirmation = '';

    public $current_password = '';
    public $current_hashed_password;

    protected $listeners = [
        'resetPasswordComponent',
        'resetTimeElapsed'
    ];

    public function mount()
    {
        $this->userId = auth()->id();
        $model = User::find($this->userId);
        $this->current_hashed_password = $model->password;
    }

    public function render()
    {
        return view('livewire.user-reset-password-profile-component');
    }

    public function updated($password)
    {
        $this->validateOnly($password, [
            'current_password' => [ 'required', 'customPassCheckHashed:'.$this->current_hashed_password],
            'password' => [
                \Illuminate\Validation\Rules\Password::min(16)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
                'required_with:current_password'
            ],
            'password_confirmation' => 'min:8|required_with:password|same:password'
        ]);
    }

    public function save()
    {
        $this->validate([
            'current_password' => [ 'required', 'customPassCheckHashed:'.$this->current_hashed_password],
            'password' => [
                \Illuminate\Validation\Rules\Password::min(16)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
                'required_with:current_password'
            ],
            'password_confirmation' => 'min:16|required_with:password|same:password'
        ]);
        $user = User::find($this->userId);
        $user->update(['password' => $this->password]);
        $this->resetPasswordComponent();
        $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.Updated Password!')]);
    }

    public function resetPasswordComponent()
    {
        $this->reset(['current_password','password','password_confirmation']);
        $this->resetValidation();
    }

}
