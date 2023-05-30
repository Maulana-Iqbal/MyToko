<?php

namespace App\View\Components;

use App\Repositori\StockRepositori;
use Illuminate\View\Component;

class ProdukTerbaru extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    protected $stockRepo;
    public function __construct()
    {
        $this->stockRepo=new StockRepositori();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $terbaru=$this->stockRepo->terbaru()->take(8);
        // dd($terbaru);
        return view('components.produk-terbaru',compact('terbaru'));
    }
}
