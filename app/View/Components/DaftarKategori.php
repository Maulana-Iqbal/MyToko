<?php

namespace App\View\Components;

use App\Services\KategoriService;
use Illuminate\View\Component;

class DaftarKategori extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    protected $kategori;
    public function __construct()
    {
        $this->kategori=new KategoriService();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $kategori=$this->kategori->getAll();
        return view('components.daftar-kategori',compact('kategori'));
    }
}
