@extends('layouts.app')
@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">

            <h4 class="page-title">Detail Transfer Barang</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-12">
        <div class="card p-2 m-2">
            <div class="card-body">
                @if(session('alert')=='error')
                <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <strong>Error - </strong> {{session('message')}}
                </div>
                @endif
                @if(session('alert')=='success')
                <div class="alert alert-primary alert-dismissible bg-primary text-white border-0 fade show" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <strong>Success - </strong> {{session('message')}}
                </div>
                @endif
                <!-- Invoice Logo-->
                <div class="clearfix">
                    <!-- <div class="float-start">
                        <img src="{{url('image/website/'.website()->icon)}}" alt="" height="30">
                    </div> -->
                    <div class="float-start">
                        <h4 class="m-0 d-print-none">Detail Transfer Barang</h4>
                    </div>
                </div>

                <!-- Invoice Detail-->
                <div class="row mt-2">
                    <div class="col-sm-6">


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
                        <h5>Toko</h5>
                        <span class="font-13"><strong>Toko: </strong> {{website($data->website_id)->nama_website}}</span><br>
                        <span class="font-13"><strong>Alamat: </strong> {{website($data->website_id)->webkecamatan->name}}, {{website($data->website_id)->webkota->name}}<br>
                            {{website($data->website_id)->webprovinsi->name}}, {{website($data->website_id)->pos}}</span><br>
                        <span class="font-13"><strong>HP: </strong> {{website($data->website_id)->contact}}</span>
                    </div>
                    <div class="col-sm-4">
                        <h5>Dari Gudang</h5>
                        <?php
                        $gudang1 = $data->stock_jenis->where('jenis', 2)->first()->gudang;
                        ?>
                        <span class="font-13"><strong>Kode: </strong> {{$gudang1->kode}}</span><br>
                        <span class="font-13"><strong>Nama: </strong> {{$gudang1->nama}}</span><br>
                        <span class="font-13"><strong>Jenis: </strong> @if($gudang1->jenis==1) Inventory @else Factory @endif</span><br>
                        <span class="font-13"><strong>Alamat: </strong> {{$gudang1->alamat}}</span><br>
                        <span class="font-13"><strong>Admin: </strong> {{$gudang1->user->name}}</span><br>
                    </div>
                    <div class="col-sm-4">
                        <h5>Ke Gudang</h5>
                        <?php
                        $gudang2 = $data->stock_jenis->where('jenis', 1)->first()->gudang;
                        ?>
                        <span class="font-13"><strong>Kode: </strong> {{$gudang2->kode}}</span><br>
                        <span class="font-13"><strong>Nama: </strong> {{$gudang2->nama}}</span><br>
                        <span class="font-13"><strong>Jenis: </strong> @if($gudang2->jenis==1) Inventory @else Factory @endif</span><br>
                        <span class="font-13"><strong>Alamat: </strong> {{$gudang2->alamat}}</span><br>
                        <span class="font-13"><strong>Admin: </strong> {{$gudang2->user->name}}</span><br>
                    </div>
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-12">
                        <h5>Data Transfer</h5>
                        <div class="table-responsive">
                            <table class="table mt-2" style="min-width: 900px;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Dari Gudang</th>
                                        <th>Ke Gudang</th>
                                        <th>Barang</th>
                                        <th>Kategori</th>
                                        <th>Satuan</th>
                                        <th>Jumlah</th>
                                        <th>Harga</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jenis->where('jenis',2) as $index=>$d)
                                    <tr>
                                        <td>{{$index+1}}</td>
                                        <td>{{$d->gudang->nama}}</td>
                                        <td>{{$d->stock_transfer->ke_gudang->nama}}</td>
                                        <td>
                                            <img width="40px" src="{{url('image/produk/small'.'/'.$d->produk->gambar_utama)}}" alt="">
                                            {{$d->produk->nama_produk}}
                                        </td>
                                        <td>{{$d->produk->kategori->nama_kategori}}</td>
                                        <td>{{$d->produk->satuan->name}}</td>
                                        <td>{{$d->jumlah_total}}</td>
                                        <td>{{uang($d->harga_final)}}</td>
                                        <td class="text-end">
                                            <?php
                                            $subTotal = $d->jumlah_total * $d->harga_final;
                                            ?>
                                            {{uang($subTotal)}}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> <!-- end table-responsive-->
                    </div> <!-- end col -->
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-12">
                        <h5>Transfer Detail</h5>
                        <div class="table-responsive">
                            <table class="table mt-2">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Gudang</th>
                                        <th>Barang</th>
                                        <th>Satuan</th>
                                        <th>Stock Awal</th>
                                        <th>Jumlah</th>
                                        <th>Stock Akhir</th>
                                        <th>Jenis</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jenis->sortBy('created_at') as $index=>$d)
                                    <tr>
                                        <td>{{$index+1}}</td>
                                        <td>{{$d->gudang->nama}}</td>
                                        <td>
                                            <img width="40px" src="{{url('image/produk/small'.'/'.$d->produk->gambar_utama)}}" alt="">
                                            {{$d->produk->nama_produk}}
                                        </td>
                                        <td>{{$d->produk->satuan->name}}</td>
                                        <td>{{$d->stock_awal}}</td>
                                        <td> @if($d->jenis==1)
                                            + {{$d->jumlah}}
                                            @elseif($d->jenis==2)
                                            - {{$d->jumlah}}
                                            @endif</td>
                                        <td>
                                            @if($d->jenis==1)
                                            {{$d->stock_awal+$d->jumlah}}
                                            @elseif($d->jenis==2)
                                            {{$d->stock_awal-$d->jumlah}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($d->jenis==1) <span class="text-primary">Masuk</span> @elseif($d->jenis==2) <span class="text-danger">Keluar</span> @endif
                                        </td>
                                        <td>
                                            @if($d->valid==1) <span class="text-primary">Selesai</span> @elseif($d->valid==2) <span class="text-danger">Menunggu</span> @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> <!-- end table-responsive-->
                    </div> <!-- end col -->
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-sm-6">
                        <div class="clearfix pt-3">
                            <h6 class="text-muted">Notes:</h6>
                            <small>
                                {!!$data->deskripsi!!}
                            </small>
                            <h6 class="text-muted pt-2">Bukti Kirim:</h6>
                            @if($data->file_kirim)
                            <a href="{{url('image/transfer-stock/kirim/'.$data->file_kirim)}}" target="_blank">{{$data->file_kirim}}</a>
                            @else
                            <span class="text-danger">Belum Ada</span>
                            @endif
                            <h6 class="text-muted pt-2">Bukti Terima:</h6>
                            @if($data->file_terima)
                            <a href="{{url('image/transfer-stock/terima/'.$data->file_terima)}}" target="_blank">{{$data->file_terima}}</a>
                            @else
                            <span class="text-danger">Belum Ada</span>
                            @endif
                        </div>
                    </div> <!-- end col -->
                    <div class="col-sm-6">
                        <div class="float-end mt-3 mt-sm-0">
                            <p><b>Sub Total : </b> <span class="float-end">{{uang($data->order_total)}}</span></p>
                            <p><b>PPN: </b> <span class="float-end">{{uang($data->tax)}}</span></p>
                            <p><b>Pengiriman : </b> <span class="float-end">{{uang($data->pengiriman)}}</span></p>
                            <p><b>Diskon : </b> <span class="float-end">{{uang($data->diskon)}} (-)</span></p>
                            <h3>{{uang($data->total)}}</h3>
                        </div>
                        <div class="clearfix"></div>
                    </div> <!-- end col -->
                </div>
                <!-- end row-->

                <div class="d-print-none mt-4">

                    <div class="text-end">
                        @if($data->status_order=='dikirim')
                        @if(auth()->user()->can('stocktransfer') or auth()->user()->can('stocktransfer-terima'))
                        <a href="javascript:void(0)" id="konfirmasi" class="btn btn-success"><i class="mdi mdi-send"></i> Konfirmasi Terima Barang</a>
                        @endif
                        @endif
                        <a href="javascript:window.print()" class="btn btn-primary"><i class="mdi mdi-printer"></i> Print</a>
                    </div>
                </div>
                <!-- end buttons -->

            </div> <!-- end card-body-->
        </div> <!-- end card -->
    </div> <!-- end col-->
</div>
<!-- end row -->
<div id="ajaxModel" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="success-header-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-info">
                <h4 class="modal-title" id="success-header-modalLabel">Terima Barang</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="{{url('stock-transfer/terima')}}" method="post" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id" value="{{enc($data->id)}}" id="id">
                    <div class="mb-2">
                        <label for="file" class="control-label">Upload Bukti Terima Barang</label>

                        <input type="file" class="form-control" id="file" name="file" required="">
                        <small>Hanya diperbolehkan dengan extensi : <b style="color: darkred;"> .jpg /
                                .jpeg / .zip / .rar</b> |
                            Maksimal Ukuran 2024KB</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">Konfirmasi</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
    $(document).ready(function() {
        $(document).on("click", "#konfirmasi", function() {
            $("#ajaxModel").modal('show');
        })
    })
</script>
@endsection