<?php

namespace App\Http\Livewire;

use App\Models\Tenant\User;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Actions\ConfirmPassword;
use Livewire\Component;

class ConfirmPasswordForTwoFactorComponent extends Component
{

    public $userId;

    public $current_hashed_password;

    protected $listeners = [
        'startConfirm',
    ];

    protected $rules = [
        'confirmablePassword' => 'required'
    ];

    public function mount()
    {
        $this->userId = auth()->user()->id;
        $model = User::find($this->userId);

        $this->current_hashed_password = $model->password;
    }

    public function render()
    {
        return view('livewire.confirm-password-for-two-factor-component');
    }

    /**
     * Indicates if the user's password is being confirmed.
     *
     * @var bool
     */
    public $confirmingPassword = false;

    /**
     * The ID of the operation being confirmed.
     *
     * @var string|null
     */
    public $confirmableId = null;

    /**
     * The user's password.
     *
     * @var string
     */
    public $confirmablePassword = '';

    public function updated($confirmablePassword)
    {
        $this->validateOnly($confirmablePassword, [
            'confirmablePassword' => [ 'required', 'customPassCheckHashed:'.$this->current_hashed_password]
        ]);
    }

    /**
     * Listen for event to start confirming.
     *
     * @return void
     */
    public function startConfirm()
    {
        $this->startConfirmingPassword($this->userId);
    }

    /**
     * Start confirming the user's password.
     *
     * @param  string  $confirmableId
     * @return void
     */
    public function startConfirmingPassword(string $confirmableId)
    {
        $this->resetErrorBag();

        if (!$this->passwordIsConfirmed()) {
            $this->confirmingPassword = true;
            $this->confirmableId = $confirmableId;
            $this->confirmablePassword = '';
            $this->dispatchBrowserEvent('open-confirming-password');
        }
    }

    /**
     * Stop confirming the user's password.
     *
     * @return void
     */
    public function stopConfirmingPassword()
    {
        $this->confirmingPassword = false;
        $this->confirmableId = null;
        $this->confirmablePassword = '';
    }

    /**
     * Confirm the user's password.
     *
     * @return void
     */
    public function confirmPassword()
    {
        if (app(ConfirmPassword::class)(app(StatefulGuard::class), Auth::user(), $this->confirmablePassword) && $this->validate()) {

            session(['auth.password_confirmed_at' => time()]);

            $this->dispatchBrowserEvent('close-confirming-password');

            $this->emit('passwordConfirmed', true);

            $this->stopConfirmingPassword();
        }
    }

    /**
     * Ensure that the user's password has been recently confirmed.
     *
     * @param  int|null  $maximumSecondsSinceConfirmation
     * @return void
     */
    protected function ensurePasswordIsConfirmed($maximumSecondsSinceConfirmation = null)
    {
        $maximumSecondsSinceConfirmation = $maximumSecondsSinceConfirmation ?: config('auth.password_timeout', 900);

        return $this->passwordIsConfirmed($maximumSecondsSinceConfirmation) ? null : abort(403);
    }

    /**
     * Determine if the user's password has been recently confirmed.
     *
     * @param  int|null  $maximumSecondsSinceConfirmation
     * @return bool
     */
    protected function passwordIsConfirmed($maximumSecondsSinceConfirmation = null)
    {
        $maximumSecondsSinceConfirmation = $maximumSecondsSinceConfirmation ?: config('auth.password_timeout', 900);

        return (time() - session('auth.password_confirmed_at', 0)) < $maximumSecondsSinceConfirmation;
    }
}
