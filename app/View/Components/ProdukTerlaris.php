<?php

namespace App\View\Components;

use App\Repositori\OrderRepositori;
use Illuminate\View\Component;

class ProdukTerlaris extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    protected $orderRepo;
    public function __construct()
    {
        $this->orderRepo=new OrderRepositori();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $terlaris=$this->orderRepo->terlaris()->take(8);
        return view('components.produk-terlaris',compact('terlaris'));
    }
}
