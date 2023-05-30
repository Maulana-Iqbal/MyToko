<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class NotifikasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $notifikasi=Notifikasi::where('toId',auth()->user()->id)->orWhere('toLevel',auth()->user()->level)->orderBy('id','desc');
        $notif='';
        if($request->ajax()){
            foreach ($notifikasi->get() as $value) {
                $created=Carbon::parse($value->created_at)->isoFormat("D MMMM Y");
                $notif.='<a href="'.$value->url.'" data-id="'.$value->id.'" class="dropdown-item notify-item">
                <div class="notify-icon bg-primary">
                    <i class="mdi mdi-comment-account-outline"></i>
                </div>
                <p class="notify-details">'.$value->title.'
                <small class="text-muted">'.$value->body.'</small>
                    <small class="text-muted"><time>'.$created.'</time></small>
                </p>
            </a>';
            }
            return $notif;
        }
    }

    public function ApiNotif(Request $request)
    {
        $notifikasi=Notifikasi::where('toId',auth()->user()->id)->orWhere('toLevel',auth()->user()->level)->orderBy('id','desc')->get();
        return response()
            ->json(['message' => 'Berhasil mengambil data Notifikasi', 'token_type' => 'Bearer', 'data' => $notifikasi]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Notifikasi  $notifikasi
     * @return \Illuminate\Http\Response
     */
    public function show(Notifikasi $notifikasi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Notifikasi  $notifikasi
     * @return \Illuminate\Http\Response
     */
    public function edit(Notifikasi $notifikasi)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Notifikasi  $notifikasi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $notifikasi=Notifikasi::find($request->id);
        $notifikasi->status=1;
        $notifikasi->save();
        return true;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notifikasi  $notifikasi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notifikasi $notifikasi)
    {
        //
    }
}
