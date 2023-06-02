<?php

namespace App\View\Components;

use Illuminate\View\Component;

class StatCard extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $title, $count, $color;
    public function __construct($title, $count, $color = 'bg-green-500')
    {
        $this->title = $title;
        $this->count = $count;
        $this->color = $color;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.stat-card');
    }
}
