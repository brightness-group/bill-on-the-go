<?php

namespace App\Http\Livewire\Tenant;

use Livewire\Component;

class PageAccountSetting extends Component
{
    public $tariffSelectedColor;

    public $fromCallback;

    public function render()
    {
        $container = 'container';

        $this->fromCallback = request()->get('fromCallback', false);

        return view('livewire.tenant.page-account-settings')
            ->extends('tenant.theme-new.layouts.layoutMaster', compact('container'))
            ->section('content');
    }

    public function updatedTariffSelectedColor($value)
    {
        $this->emit('changeColor', $value);
    }
}
