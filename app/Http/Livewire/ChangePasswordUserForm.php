<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;

class ChangePasswordUserForm extends Component
{

    public $userId;
    public $password = '';
    public $password_confirmation = '';

    public $current_password = '';
    public $current_hashed_password;

    protected $listeners = [
        'getUserModelId',
        'resetPasswordComponent',
    ];

    public function render()
    {
        return view('livewire.change-password-user-form');
    }

    public function getUserModelId($modelId)
    {
        $this->userId = $modelId;
        $model = User::find($this->userId);
        $this->current_hashed_password = $model->password;
    }

    public function updated($password)
    {
        $this->validateOnly($password, [
            'current_password' => ['required','customPassCheckHashed:' . $this->current_hashed_password],
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
            'current_password' => ['required', 'customPassCheckHashed:' . $this->current_hashed_password],
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
        $user->update(['password' => $this->password, 'password_updated_at' => now(), 'password_update_remind_at' => now()]);
        $this->resetPasswordComponent();
        $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.Updated Password!')]);
        $this->dispatchBrowserEvent('closeChangePasswordUserModal');
    }

    public function resetPasswordComponent()
    {
        $this->reset(['current_password','password','password_confirmation']);
        $this->resetValidation();
    }

    public function closeFormModal()
    {
        $this->emit('closeChangePasswordUserModal');
    }
}
