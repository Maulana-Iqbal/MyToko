@extends('layouts.app')
@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">

            <h4 class="page-title">Detail Penjualan</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-12">
        <div class="card p-2 m-2">
            <div class="card-body">

                <!-- Invoice Logo-->
                <div class="clearfix">
                    <!-- <div class="float-start">
                        <img src="{{url('image/website/'.website()->icon)}}" alt="" height="30">
                    </div> -->
                    <div class="float-start">
                        <h4 class="m-0">Detail Penjualan</h4>
                    </div>
                </div>

                <!-- Invoice Detail-->
                <div class="row mt-2">
                    <div class="col-sm-6">
                    <!-- <h5>Dari</h5> -->
                        <span class="font-13"><strong>Toko: </strong> {{website($data->website_id)->nama_website}}</span><br>
                        <span class="font-13"><strong>Alamat: </strong> {{website($data->website_id)->webkecamatan->name}}, {{website($data->website_id)->webkota->name}}<br>
                            {{website($data->website_id)->webprovinsi->name}}, {{website($data->website_id)->pos}}</span><br>
                        <span class="font-13"><strong>HP: </strong> {{website($data->website_id)->contact}}</span>

                    </div> <!-- end col-->
                    <div class="col-sm-4 offset-sm-2">
                        <div class="float-sm-end">
                            <p class="font-13"><strong>Tanggal: </strong> &nbsp;&nbsp;&nbsp; {{tglIndo($data->tgl)}}</p>
                            <p class="font-13"><strong>Status: </strong> <span class="badge bg-success float-end">{{strtoupper($data->status_order)}}</span></p>
                            <p class="font-13"><strong>No. Nota: </strong> <span class="float-end">{{$data->nomor}}</span></p>
                        </div>
                    </div><!-- end col -->
                </div>
                <!-- end row -->

                <div class="row">
                    
                    <div class="col-sm-4">
                        <h5>Sales</h5>
                        <span class="font-13"><strong>Nama: </strong> {{$data->sales->nama}}</span><br>
                        <span class="font-13"><strong>Alamat: </strong> {{$data->sales->alamat}}</span><br>
                        <span class="font-13"><strong>HP: </strong> {{$data->sales->telepon}}</span><br>
                    </div>
                    <div class="col-sm-4">
                        <h5>Customer</h5>
                        <span class="font-13"><strong>Nama: </strong> {{$data->pelanggan->nama_depan}} {{$data->pelanggan->nama_belakang}}</span><br>
                        <span class="font-13"><strong>Alamat: </strong> {{$data->pelanggan->alamat}}</span><br>
                        <span class="font-13"><strong>HP: </strong> {{$data->pelanggan->hp}}</span><br>
                    </div>
                    <div class="col-sm-4">
                        
                    </div>
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-12">
                        <table class="table mt-2" style="min-width: 900px;">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Satuan</th>
                                    <th>Harga</th>
                                    <th>Biaya Jasa</th>
                                    <th>Harga + Biaya Jasa</th>
                                    <th>Jumlah</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $tHarga = 0;
                                $tBiaya = 0;
                                $tHargaFinal = 0;
                                $tJumlah = 0;
                                $tTotal = 0;
                                ?>
                                @foreach($data->stock_jenis as $index=>$d)
                                <tr>
                                    <td>{{$index+1}}</td>
                                    <td>
                                        <img width="40px" src="{{url('image/produk/small'.'/'.$d->produk->gambar_utama)}}" alt="">
                                        {{$d->produk->nama_produk}}
                                    </td>
                                    <td>{{$d->produk->satuan->name}}</td>
                                    <td>{{uang($d->harga_final-$d->biaya)}}</td>
                                    <td>{{uang($d->biaya)}}</td>
                                    <td>{{uang($d->harga_final)}}</td>
                                    <td>{{$d->jumlah}}</td>
                                    <td class="text-end">
                                        <?php
                                        $subTotal = $d->jumlah * $d->harga_final;
                                        ?>
                                        {{uang($subTotal)}}
                                    </td>
                                </tr>
                                <?php
                                $tHarga += ($d->harga_final - $d->biaya)*$d->jumlah;
                                $tBiaya += $d->biaya*$d->jumlah;
                                $tHargaFinal += $d->harga_final*$d->jumlah;
                                $tJumlah += $d->jumlah;
                                $tTotal += $subTotal;
                                ?>
                                @endforeach

                            </tbody>
                            <tfoot class="table-light">
                            <tr>
                                    <td colspan="3">Sub Total</td>
                                    <td>{{uang($tHarga)}}</td>
                                    <td>{{uang($tBiaya)}}</td>
                                    <td>{{uang($tHargaFinal)}}</td>
                                    <td>{{$tJumlah}}</td>
                                    <td>{{uang($tTotal)}}</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div> <!-- end col -->
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-sm-4">
                        <div class="clearfix pt-3">
                            <h6 class="text-muted">Notes:</h6>
                            <small>
                                {!!$data->deskripsi!!}
                            </small>
                        </div>
                    </div> 
                    <div class="col-sm-4">
                        <div class="float-end mt-3 mt-sm-0">
                        <p><b>Sub Total : </b> <span class="float-end">{{uang($data->order_total)}}</span></p>
                            <p><b>PPN: </b> <span class="float-end">
                                    <?php
                                    $ppnRp = ($data->total_harga / 100) * $data->ppn;
                                    echo uang($ppnRp);
                                    ?>
                                    ({{$data->ppn}}%)
                                </span></p>
                            <p><b>PPH: </b> <span class="float-end">
                                    <?php
                                    $pphRp = ($data->total_biaya / 100) * $data->pph;
                                    echo uang($pphRp);
                                    ?>
                                    ({{$data->pph}}%)
                                </span></p>
                            
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="col-sm-4">
                        <div class="float-end mt-3 mt-sm-0">
                            <p><b>Pengiriman : </b> <span class="float-end">{{uang($data->pengiriman)}}</span></p>
                            <p><b>Biaya Lain : </b> <span class="float-end">{{uang($data->biaya_lain)}}</span></p>
                            <p><b>Diskon : </b> <span class="float-end">{{uang($data->diskon)}} (-)</span></p>
                            <h3>{{uang($data->total)}}</h3>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <!-- end row-->

                <div class="d-print-none mt-4">
                    <div class="text-end">
                        <a href="javascript:window.print()" class="btn btn-primary"><i class="mdi mdi-printer"></i> Print</a>
                    </div>
                </div>
                <!-- end buttons -->

            </div> <!-- end card-body-->
        </div> <!-- end card -->
    </div> <!-- end col-->
</div>
<!-- end row -->

@endsection