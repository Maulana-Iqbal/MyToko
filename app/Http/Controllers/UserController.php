<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use DB;
use Illuminate\Support\Arr;
use DataTables;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:user|user-list|user-create|user-edit|user-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:user|user-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:user|user-edit', ['only' => ['edit', 'store']]);
        $this->middleware('permission:user|user-delete', ['only' => ['destroy']]);
        $this->middleware('permission:user|user-profil', ['only' => ['updateProfil']]);
    }

    public function index(Request $request)
    {
        $pengguna = User::latest();
        $roles = Role::query();
        if(auth()->user()->hasPermissionTo('show-all')){

        }else{
        $pengguna->where('website_id',website()->id);
        $roles->where('website_id',website()->id);
        }
        $pengguna->get();
        $roles=$roles->get();
        if ($request->ajax()) {
            return Datatables::of($pengguna)
                ->addIndexColumn()
                ->addColumn('foto', function ($row) {
                    $foto='';
                    if($row->foto<>null){
                        $foto="<a href='/userFoto/" . $row->foto . "'><img width='50px' src='userFoto/" . $row->foto . "'/></a>";
                    }
                    return $foto;
                })
                ->addColumn('website_id', function ($row) {
                    $website='';
                     if($row->website<>null){
                        $website=$row->website->nama_website;
                     }
                    return $website;
                })
                ->addColumn('action', function ($row) {
                    $btn = "";
                    if (auth()->user()->hasPermissionTo('user') or auth()->user()->hasPermissionTo('user-edit')) {
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_pengguna="' . enc($row->id) . '" data-original-title="Edit" class="edit btn btn-outline-primary btn-sm editPengguna"> <i class="mdi mdi-square-edit-outline"></i></a>';
                    }
                    if (auth()->user()->hasPermissionTo('user') or auth()->user()->hasPermissionTo('user-delete')) {
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_pengguna="' . enc($row->id) . '" data-original-title="Delete" class="btn btn-outline-danger btn-sm deletePengguna"> <i class="mdi mdi-delete"></i></a>';
                    }

                    return $btn;
                })
                ->rawColumns(['name', 'email', 'level', 'foto', 'website_id', 'action'])
                ->make(true);
        }
        return view('user/user', compact('pengguna','roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function profil(Request $request)
    {
        $user = User::where('username',$request->username)->firstOrFail();
        return view('user/profil', compact(['user']));
    }

    public function ubahProfil(Request $request)
    {
        $user = User::where('username',$request->username)->firstOrFail();
        return view('user/profil', compact(['user']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id="";

        if (empty($request->id_pengguna)) {
            $request->validate([
                'nama_lengkap' => 'required',
                'username' => 'required|unique:users,username',
                'level' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                'repassword' => 'required|same:password',
                'website'=>'required',
            ], [
                'required' => 'Silahkan lengkapi data',
                'email.unique' => 'Email sudah terdaftar',
                'username.unique' => 'Username sudah terdaftar',
                'same' => 'Kombinasi Password tidak valid',
            ]);
            if ($request->file) {
                $request->validate([
                    'file' => 'mimes:ico,png,jpg,jpeg|max:1024',
                ], [
                    'max' => 'Maksimal Kapasitas Icon 1024KB/1MB',
                    'mimes' => 'Ekstensi Icon Diizinkan .ico / .png / .jpg / .jpeg'
                ]);
                $filename = time() . '.' . $request->file->extension();
                $request->file->move(public_path('userFoto'), $filename);
            } else {
                $filename = '';
            }
            $token = '';
            $password = Hash::make($request->password);
        } else {
            $id=$request->id_pengguna;
            $user = User::find($id);
            $request->validate([
                'nama_lengkap' => 'required|max:100',
                'level' => 'required',
                'email' => 'required|email|unique:users,email,' . $id,
                'username' => 'required|max:100|unique:users,username,' . $id,
                'website' => 'required',
            ], [
                'required' => 'Silahkan lengkapi data',
                'email.unique' => 'Email sudah terdaftar',
                'username.unique' => 'Username sudah terdaftar',
            ]);
            if ($request->file) {
                $request->validate([
                    'file' => 'mimes:ico,png,jpg,jpeg|max:1024',
                ], [
                    'max' => 'Maksimal Kapasitas Icon 1024KB/1MB',
                    'mimes' => 'Ekstensi Icon Diizinkan .ico / .png / .jpg / .jpeg'
                ]);
                $filename = time() . '.' . $request->file->extension();
                $request->file->move(public_path('userFoto'), $filename);
            } else {
                $filename = $user->foto;
            }

            $token = '';
            if (!empty($request->password and !empty($request->repassword))) {
                $request->validate([
                    'password' => 'required',
                    'repassword' => 'required|same:password'
                ], [
                    'same' => 'Kombinasi Password tidak valid',
                ]);
                $password = Hash::make($request->password);
            } else {
                $password = $user->password;
            }
        }



        $user=User::updateOrCreate([
            'id' => $id
        ], [
            'name' => $request->nama_lengkap,
            'username' => $request->username,
            'level' => $request->level,
            'email' => $request->email,
            'foto' => $filename,
            'userToken' => $token,
            'password' => $password,
            'website_id'=>$request->website,
        ]);
        DB::table('model_has_roles')->where('model_id',$id)->delete();
        $user->assignRole($request->input('roles'));
        // return response
        $response = [
            'success' => true,
            'message' => 'Berhasil Disimpan.',
        ];
        return response()->json($response, 200);
    }



    public function updateProfil(Request $request)
    {
        $id=dec($request->id_pengguna);
        $request->validate([
            'id_pengguna' => 'required',
            'nama_lengkap' => 'required|max:100',
            'email' => 'required|email|unique:users,email,' . $id,
            'username' => 'required|max:100|unique:users,username,' . $id,
        ], [
            'required' => '',
            'email' => 'Alamat Email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'username.unique' => 'Username sudah terdaftar',
            'username.max' => 'Maksimal Username 100 Karakter',
        ]);



        $profil = User::find($id);
        $profil->name = $request->nama_lengkap;
        $profil->email = $request->email;
        $profil->username = $request->username;
        if (!empty($request->password and !empty($request->repassword))) {
            $request->validate([
                'password' => 'required',
                'repassword' => 'required|same:password'
            ], [
                'same' => 'Kombinasi Password tidak valid',
                'required' => 'Kombinasi Password tidak valid'
            ]);
            $profil->password = Hash::make($request->password);
        }
        if ($request->file) {
            $request->validate([
                'file' => 'mimes:ico,png,jpg,jpeg|max:1024',
            ], [
                'max' => 'Maksimal Kapasitas Icon 1024KB/1MB',
                'mimes' => 'Ekstensi Icon Diizinkan .ico / .png / .jpg / .jpeg'
            ]);
            $filename = time() . '.' . $request->file->extension();
            $request->file->move(public_path('userFoto'), $filename);
            $profil->foto = $filename;
        }
        $profil->save();
        return redirect()->back()->with(['status' => 'success', 'message' => 'Berhasil Diperbaharui!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id=dec($id);
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name')->all();
        return response()->json(['success'=>true,'message'=>'Berhasil','data'=>$user,'roles'=>$roles,'userRole'=>$userRole],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id=dec($id);
        if($id==1){
            $response = [
                'success' => false,
                'message' => 'Data ini tidak dapat dihapus .',
            ];
            return response()->json($response, 200);
        }
        $del = User::destroy($id);
        // return response
        $response = [
            'success' => true,
            'message' => 'Berhasil Dihapus.',
        ];
        return response()->json($response, 200);
    }

    public function sendEmail($title, $body, $to)
    {
        $details = [
            'title' => $title,
            'body' => $body,
        ];
        \Mail::to($to)->send(new \App\Mail\MyTestMail($details));
        return;
    }


    public function select(Request $request){
        $search = $request->search;

        if (auth()->user()->hasPermissionTo('show-all')) {
            $q=null;
        }else{
        $q=['website_id'=>website()->id];
        }

        if($search == ''){
           $data = User::where($q)->orderBy('name','asc')->select('id','name')->get();
        }else{
           $data = User::where($q)->orderBy('name','asc')->select('id','name')->where('name', 'like', '%' .$search . '%')->get();
        }

        $response = array();
        foreach($data as $data){
           $response[] = array(
                "id"=>$data->id,
                "text"=>$data->name
           );
        }
        return response()->json($response);
     }
}
