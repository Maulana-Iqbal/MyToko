<?php

namespace App\Repositori;

use App\Models\Invoice;
class InvoiceRepositori
{
    protected $invoice;
    public function __construct()
    {
        $this->invoice=new Invoice();
    }

    public function updateOrCreate($id,$data)
    {
        return $this->invoice->updateOrCreate($id,$data);
    }

    public function getAll()
    {
        return $this->invoice->get();
    }

    public function getId($id){
        return $this->invoice->find($id);
    }

    public function getWhere($data){
        return $this->invoice->where($data);
    }

    public function store($request){
        return $this->invoice->create($request);
    }

    public function update($id,$data){
      return  $this->getId($id)->update($data);
    }

}
