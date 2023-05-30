<?php

namespace App\Services;

use App\Http\Requests\KasBankRequest;
use App\Repositori\KasBankRepositori;

use DataTables;

class KasBankService
{
    protected $kasBankRepo;

    public function __construct()
    {
        $this->kasBankRepo = new KasBankRepositori();
    }

    public function getAll()
    {
        return $this->kasBankRepo->getAll();
    }

    public function getId($id)
    {
        return $this->kasBankRepo->getId($id);
    }

    public function getWhere($data)
    {
        return $this->kasBankRepo->getWhere($data);
    }

    public function getAllTrashed()
    {
        return $this->kasBankRepo->getAllTrashed();
    }

    public function store($request)
    {
        $id = $request->id_kasBank;
        $data = [
            'id' => $id,
            // 'tipe'=>$request->tipe,
            // 'induk'=>$request->induk,
            'akun_id'=>$request->akun,
            'website_id'=>website()->id,
        ];

        return $this->kasBankRepo->store($data);
    }

    public function getDatatable($kasBank, $type)
    {
        try {
            return Datatables::of($kasBank)
                ->addIndexColumn()
                ->addColumn('select', function ($row) {
                    return '<input type="checkbox" name="select[]" class="form-check-input select" value="' . enc($row->id) . '" />';
                })
                ->addColumn('tipe', function ($row) {
                    $tipe='';
                    if($row->akun->tipe<>null){
                        $tipe=$row->akun->tipe;
                    }
                    return $tipe;
                })
                ->addColumn('kode', function ($row) {
                    $kode='';
                    if($row->akun<>null){
                        $kode=$row->akun->kode;
                    }
                    return $kode;
                })
                ->addColumn('name', function ($row) {
                    $name=$row->akun->name;
                    if($row->tipe=='detail'){
                        $name='<ul><li>'.$row->akun->name.'</li></ul>';
                    }
                    return $name;
                })
                ->addColumn('kategori', function ($row) {
                    $kategori='';
                    if($row->akun->kategori_akun<>null){
                        $kategori=$row->akun->kategori_akun->name;
                    }
                    return $kategori;
                })
                ->addColumn('saldo', function ($row) {
                    $saldo='';
                    if($row->akun->tipe=='detail'){
                    $debit=$row->akun->kas->where('akun_id',$row->akun_id)->sum('debit');
                    $kredit=$row->akun->kas->where('akun_id',$row->akun_id)->sum('kredit');

                    // $debit=$row->kas->where('akun_id',$row->id)->sum('debit');
                    // $kredit=$row->kas->where('akun_id',$row->id)->sum('kredit');
                    $saldo=(double)$debit-(double)$kredit;
                    }
                    return 'Rp. '.number_format($saldo, 0, ',', '.');
                })
                ->addColumn('action', function ($row) use ($type) {
                    if ($type == 1) {
                        $btn = "";
                            $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . enc($row->id) . '" data-original-title="Edit" class="edit btn btn-outline-primary btn-xs editKasBank"> <i class="mdi mdi-square-edit-outline"></i></a>';

                            $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . enc($row->id) . '" data-status="trash" data-original-title="Delete" class="btn btn-outline-danger btn-xs deleteKasBank"> <i class="mdi mdi-delete"></i></a>';

                        return $btn;
                    } else {
                        $btn = "";

                            $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . enc($row->id) . '" data-original-title="Restore" class="btn btn-outline-primary restoreKasBank"> Restore</a>';
                            $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . enc($row->id) . '" data-status="delete" data-original-title="Hapus" class="btn btn-outline-danger deleteKasBank"> Permanent Delete</a>';

                        return $btn;
                    }
                })
                ->rawColumns(['tipe','kode','name','kategori','saldo','select', 'action'])
                ->make(true);
        } catch (\Throwable $th) {
            return false;
        }
    }
}
