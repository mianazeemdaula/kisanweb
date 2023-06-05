<?php

namespace App\View\Components;

use Illuminate\View\Component;

class WebPagination extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $paginator;

    public function __construct($paginator)
    {
        $this->paginator = $paginator;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.web-pagination');
    }
}
