<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FeatherIcon extends Component
{
    /**
     * Width.
     *
     * @var string
     */
    public mixed $width;

    /**
     * Height.
     *
     * @var string
     */
    public mixed $height;

    /**
     * Icon.
     *
     * @var string
     */
    public string $icon;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($icon='alert-circle',$width = 25, $height = 25)
    {
        $this->icon = $icon;
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.feather-icon');
    }
}
