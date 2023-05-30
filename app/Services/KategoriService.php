<?php

namespace App\Services;

use App\Repositori\KategoriRepositori;
use DataTables;
use Image;

class KategoriService
{
    protected $kategoriRepo;

    public function __construct()
    {
        $this->kategoriRepo = new KategoriRepositori();
    }

    public function store($request)
    {

        $id = $request->id_kategori;
        $slug = strtolower(preg_replace("/[^a-zA-Z0-9]/", "-", $request->nama_kategori));

        $cekKategori = $this->kategoriRepo->getWhere(['nama_kategori' => $request->nama_kategori, 'induk_id' => $request->induk, 'website_id' => website()->id]);
        if ($id) {
            $cekKategori->where('id', '<>', $id);
        }
        $cekKategori = $cekKategori->count();
        if ($cekKategori > 0) {
            $response = [
                'success' => false,
                'message' => 'Kategori Sudah Digunakan',
                'data'=>[]
            ];
            return $response;
        }

        if (empty($id)) {
            if ($request->file) {
                $request->validate([
                    'file' => 'required|image|mimes:ico,png,jpg,jpeg|max:220',
                ], [
                    'max' => 'Maksimal Kapasitas Icon 200KB',
                    'mimes' => 'Ekstensi Icon Diizinkan .ico / .png / .jpg / .jpeg'
                ]);
                $filename = $this->uploadImage($request->file);
            } else {
                $filename = 'default.png';
            }
        } else {
            if ($request->file) {
                $request->validate([
                    'file' => 'required|mimes:ico,png,jpg,jpeg|max:220',
                ], [
                    'max' => 'Maksimal Kapasitas Icon 200KB',
                    'mimes' => 'Ekstensi Icon Diizinkan .ico / .png / .jpg / .jpeg'
                ]);
                $filename = $this->uploadImage($request->file);
            } else {
                $cek = $this->kategoriRepo->getId($id);
                $filename = $cek->icon;
            }
        }

        $data = [
            'id' => $id,
            'induk_id' => (int)$request->induk,
            'nama_kategori' => $request->nama_kategori,
            'slug' => $slug,
            'icon' => $filename,
            'website_id' => website()->id,
        ];

        $save = $this->kategoriRepo->store($data);
        if ($save) {
            $response = [
                'success' => true,
                'message' => 'Berhasi Disimpan',
                'data' => $save,
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Gagal Disimpan',
                'data'=>[]
            ];
        }

        return $response;
    }



    public function getDatatable($kategori, $type)
    {
        try {
            return Datatables::of($kategori)
                ->addIndexColumn()
                ->addColumn('select', function ($row) {
                    return '<input type="checkbox" name="select[]" class="form-check-input select" value="' . enc($row->id) . '" />';
                })
                ->addColumn('group', function ($row) {
                    $group = '';
                    if (!empty($row->group)) {
                        $group = $row->group->nama_kategori;
                    }
                    return $group;
                })
                ->addColumn('icon', function ($row) {
                    return "<a href='/image/kategori/" . $row->icon . "'><img width='50px' src='/image/kategori/tumb/" . $row->icon . "'/></a>";
                })
                ->addColumn('action', function ($row) use ($type) {
                    if ($type == 1) {
                        $btn = "";
                        if (auth()->user()->hasPermissionTo('kategori') or auth()->user()->hasPermissionTo('kategori-edit')) {
                            $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_kategori="' . enc($row->id) . '" data-original-title="Edit" class="edit btn btn-outline-primary btn-xs editKategori"> <i class="mdi mdi-square-edit-outline"></i></a>';
                        }
                        if (auth()->user()->hasPermissionTo('kategori') or auth()->user()->hasPermissionTo('kategori-trash')) {
                            $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_kategori="' . enc($row->id) . '" data-status="trash" data-original-title="Delete" class="btn btn-outline-danger btn-xs deleteKategori"> <i class="mdi mdi-delete"></i></a>';
                        }

                        return $btn;
                    } else {
                        $btn = "";
                        if (auth()->user()->hasPermissionTo('kategori') or auth()->user()->hasPermissionTo('kategori-trash')) {
                            $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_kategori="' . enc($row->id) . '" data-original-title="Restore" class="btn btn-outline-primary restoreKategori"> Restore</a>';
                        }
                        if (auth()->user()->hasPermissionTo('kategori') or auth()->user()->hasPermissionTo('kategori-delete')) {
                            $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_kategori="' . enc($row->id) . '" data-status="delete" data-original-title="Hapus" class="btn btn-outline-danger deleteKategori"> Permanent Delete</a>';
                        }

                        return $btn;
                    }
                })
                ->rawColumns(['select', 'group', 'nama_kategori', 'icon', 'action'])
                ->make(true);
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function uploadImage($request)
    {
        $filename = time() . '.' . $request->extension();
        $request->move(public_path('image/kategori'), $filename);
        $tumb = Image::make('image/kategori/' . $filename)->resize(40, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $patchTumb = 'image/kategori/tumb';
        if (!file_exists($patchTumb)) {
            mkdir($patchTumb, 755, true);
        }
        $tumb->save(public_path('image/kategori/tumb/' . $filename));
        return $filename;
    }
}
