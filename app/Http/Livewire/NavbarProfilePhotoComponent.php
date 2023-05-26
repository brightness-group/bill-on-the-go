<?php

namespace App\Http\Livewire;

use Livewire\Component;

class NavbarProfilePhotoComponent extends Component
{

    public $photoPath;

    public $listeners = [
        'updateProfileComponent' => '$refresh',
        'updateNavbarPhoto' => '$refresh',
    ];

    public function render()
    {
        return view('livewire.navbar-profile-photo-component');
    }

}
