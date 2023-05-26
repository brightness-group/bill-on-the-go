<?php

namespace App\Http\Livewire;

use Livewire\Component;

class LimitFieldComponent extends Component
{
    public $limit;

    public $listener = 'limitUpdate';

    public function mount($limit, $listener = 'limitUpdate')
    {
        $this->limit    = $limit;

        $this->listener = $listener;
    }

    public function render()
    {
        return view('livewire.limit-field-component');
    }

    public function updatedLimit()
    {
        $this->emit($this->listener, $this->limit);
    }
}
