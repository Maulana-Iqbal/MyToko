<?php

namespace App\Services;

use App\Repositori\OrderRepositori;
use App\Repositori\StockRepositori;
use App\Repositori\TransaksiRepositori;
use DataTables;

class TransaksiService
{
    protected $transaksiRepo;
    protected $stockRepo;
    protected $historyStockService;
    protected $orderRepo;
    public function __construct()
    {
        $this->transaksiRepo = new TransaksiRepositori();
        $this->stockRepo = new StockRepositori();
        $this->historyStockService = new HistoryStockService();
        $this->orderRepo = new OrderRepositori();
    }

    public function store($request)
    {
        return $this->transaksiRepo->store($request);
    }



   
}
