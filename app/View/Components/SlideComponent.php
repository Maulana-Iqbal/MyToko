<?php

namespace App\View\Components;

use App\Repositori\SlideRepositori;
use Illuminate\View\Component;

class SlideComponent extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    protected $slideRepository;
    public function __construct()
    {
        $this->slideRepository=new SlideRepositori();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $slide=$this->slideRepository->getWhere(['isActive'=>1])->get();
        return view('components.slide-component',compact('slide'));
    }
}
