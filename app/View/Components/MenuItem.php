<?php

namespace App\View\Components;

use Illuminate\View\Component;

class MenuItem extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $title;
    public $icon;
    public $active;
    public $url;
    public function __construct($title, $icon, $active, $url)
    {
        $this->title = $title;
        $this->active = $active;
        $this->icon = $icon;
        $this->url = $url;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.menu-item');
    }
}
