<?php

namespace App\Repositori;

use App\Models\Quotation;
class QuotationRepositori
{
    protected $quotation;
    public function __construct()
    {
        $this->quotation=new Quotation();
    }

    public function getAll()
    {
        return $this->quotation->get();
    }

    public function getId($id){
        return $this->quotation->find($id);
    }

    public function getWhere($data){
        return $this->quotation->where($data);
    }

    public function store($request){
        return $this->quotation->create($request);
    }

    public function update($id,$data){
      return  $this->getId($id)->update($data);
    }

}
