@extends('layouts.app')
@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">

            <h4 class="page-title">Detail Tagihan Pembayaran</h4>
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
                        <h4 class="m-0">Detail Tagihan Pembayaran</h4>
                    </div>
                </div>

                <!-- Invoice Detail-->
                <div class="row mt-2">
                    <div class="col-sm-6">
                        <h5>Dari</h5>
                        <span class="font-13"><strong>Toko: </strong> {{website($transaksi->website_id)->nama_website}}</span><br>
                        <span class="font-13"><strong>Alamat: </strong> {{website($transaksi->website_id)->webkecamatan->name}}, {{website($transaksi->website_id)->webkota->name}}<br>
                            {{website($transaksi->website_id)->webprovinsi->name}}, {{website($transaksi->website_id)->pos}}</span><br>
                        <span class="font-13"><strong>HP: </strong> {{website($transaksi->website_id)->contact}}</span>
                    </div>
                    <div class="col-sm-4 offset-sm-2">
                        <div class="float-sm-end">
                            <p class="font-13"><strong>Tanggal Pesanan: </strong> &nbsp;&nbsp;&nbsp; {{tglIndo($transaksi->tgl)}}</p>
                            <p class="font-13"><strong>No. Tagihan: </strong> <span class="badge bg-success float-end">{{strtoupper($invoice->no_inv)}}</span></p>
                            <p class="font-13"><strong>Jatuh Tempo: </strong> <span class="float-end">{{tglIndo($invoice->tgl_jatuh_tempo)}}</span></p>
                            <p class="font-13"><strong>Status Pesanan: </strong> <span class="float-end">{{strtoupper($transaksi->status_order)}}</span>
                            </p>
                        </div>
                    </div><!-- end col -->
                </div>
                <!-- end row -->



                <div class="row mt-4">
                    <div class="col-sm-12">
                        <table width="400px" cellpadding="0" cellspacing="0">
                            <tr>
                                <td>Kepada Yth,</td>
                            </tr>
                            <tr>
                                <td>{{$invoice->kepada}}</td>
                            </tr>
                            <tr>
                                <td>Di</td>
                            </tr>
                            <tr>
                                <td>{{$invoice->di}}</td>
                            </tr>
                        </table>
                        <br>
                        <div style="text-indent: 1cm; width: 100%; word-wrap: break-word;">
                            {!!$invoice->pembuka!!}
                        </div>
                        <br>
                    </div>
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
                                @foreach($transaksi->stock_jenis as $index=>$d)
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
                                $tHarga += ($d->harga_final - $d->biaya) * $d->jumlah;
                                $tBiaya += $d->biaya * $d->jumlah;
                                $tHargaFinal += $d->harga_final * $d->jumlah;
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
                        @if($transaksi->metode_bayar=='ewallet')
                        @if($transaksi->xeninvoice)
                        @if ($transaksi->xeninvoice->xen_status == 'PENDING')
                        <span">Scan Tagihan</span><br><br>
                            <img src="{{url('image/transaksi/qrbayar/'.$transaksi->nomor.'.svg')}}" width="80px" alt="">
                            @endif
                            @endif
                            @endif
                    </div>
                    <div class="col-sm-4">
                        <div class="float-end mt-3 mt-sm-0">
                            <p><b>Sub Total : </b> <span class="float-end">{{uang($transaksi->order_total)}}</span></p>
                            <p><b>PPN: </b> <span class="float-end">
                                    <?php
                                    $ppnRp = ($transaksi->total_harga / 100) * $transaksi->ppn;
                                    echo uang($ppnRp);
                                    ?>
                                    ({{$transaksi->ppn}}%)
                                </span></p>
                            <p><b>PPH: </b> <span class="float-end">
                                    <?php
                                    $pphRp = ($transaksi->total_biaya / 100) * $transaksi->pph;
                                    echo uang($pphRp);
                                    ?>
                                    ({{$transaksi->pph}}%) (-)
                                </span></p>

                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="col-sm-4">
                        <div class="float-end mt-3 mt-sm-0">
                            <p><b>Pengiriman : </b> <span class="float-end">{{uang($transaksi->pengiriman)}}</span></p>
                            <p><b>Biaya Lain : </b> <span class="float-end">{{uang($transaksi->biaya_lain)}}</span></p>
                            <p><b>Diskon : </b> <span class="float-end">{{uang($transaksi->diskon)}} (-)</span></p>
                            <h3>{{uang($transaksi->total)}}</h3>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-sm-12">
                        <div style="text-indent: 1cm; width: 100%; word-wrap: break-word;">
                            {!!$invoice->penutup!!}
                        </div>
                        <br><br>
                        <table border="0" width="100%" align="center" cellpadding="3" cellspacing="0">
                            <tr>
                                <td width="50%" valign="top">
                                    <h6 class="text-muted">Catatan:</h6>
                                    <small>
                                        {!!$invoice->catatan!!}
                                    </small>
                                </td>
                                <td width="50%" align="center">
                                    Hormat Kami,<br><br>
                                    TTD
                                    <!-- <img src="{{asset('image/website/'.website()->icon)}}" width="80px" alt=""> -->
                                    <br><br>
                                    {{website()->nama_website}}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

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