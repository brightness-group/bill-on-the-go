<?php

namespace App\Http\Livewire;

use Livewire\Component;

class SpinnerWindowComponent extends Component
{
    public $isVisible = false;

    public $message;

    protected $listeners = [
        'openSpinnerWindow',
        'closeSpinnerWindow'
    ];

    public function mount()
    {
        $this->message = __('locale.Please wait while retrieving data. This process must not be interrupted.') . ' ...';
    }

    public function render()
    {
        return view('livewire.spinner-window-component')
            ->extends('tenant.contentLayoutMaster');
    }

    public function openSpinnerWindow($message = null)
    {
        $this->message = __('locale.' . $message);

        $this->isVisible = true;
    }

    public function closeSpinnerWindow()
    {
        $this->isVisible = false;
    }
}
