<?php

namespace App\Http\Controllers;

use App\Repositori\OrderRepositori;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $orderRepo;
    public function __construct()
    {
        $this->orderRepo=new OrderRepositori();
    }


}
