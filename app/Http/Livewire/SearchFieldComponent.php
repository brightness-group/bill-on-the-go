<?php

namespace App\Http\Livewire;

use Livewire\Component;

class SearchFieldComponent extends Component
{
    public $search;

    public $listener = 'searchUpdate';

    public function mount($search, $listener = 'searchUpdate')
    {
        $this->search   = $search;

        $this->listener = $listener;
    }

    public function render()
    {
        return view('livewire.search-field-component');
    }

    public function updatedSearch()
    {
        $this->emit($this->listener, $this->search);
    }
}
