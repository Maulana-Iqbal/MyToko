<?php

namespace App\Repositori;

use App\Models\Pembayaran;

class PembayaranRepositori
{
protected $pembayaran;

public function __construct()
{
    $this->pembayaran=new Pembayaran();
}
    public function getAll()
    {
        return $this->pembayaran->get();
    }

    public function getId($id){
        return $this->pembayaran->find($id);
    }

    public function getWhere($data){
        return $this->pembayaran->where($data);
    }

    public function store($type,$dataId,$tglBayar,$jmlBayar,$metodeBayar,$statusBayar,$verifikasi,$buktiBayar=null,$deskripsi=null,$createdBy=null,$updatedBy=null){
        $save=$this->pembayaran->create([
            'type' => $type,
            'data_id' => $dataId,
            'tgl_bayar' => $tglBayar,
            'jml_bayar' => $jmlBayar,
            'metode_bayar'=>$metodeBayar,
            'status_bayar'=>$statusBayar,
            'verifikasi'=>$verifikasi,
            'bukti_bayar'=>$buktiBayar,
            'deskripsi'=>$deskripsi,
            'created_by'=>$createdBy,
            'updated_by'=>$updatedBy,
        ]);
        return $save->id;
    }


}
