@extends('../index')

@section('content')

<section style="margin-top: 50px; margin-bottom:50px;">
    <div class="container justify-content-center mt-50 mb-50">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-transparent header-elements-inline">
                        {{-- <h6 class="card-title">Faktur Penjualan</h6> --}}
                        <div class="header-elements">
                            <!-- <button type="button" class="btn btn-light btn-sm"><i class="fa fa-file mr-2"></i> Save</button> -->
                            <button type="button" id="btnPrint" class="btn btn-light btn-sm ml-3"><i class="fa fa-print mr-2"></i> Print</button>
                            <button type="button" id="btnPdf" class="btn btn-light btn-sm ml-3"><i class="fa fa-file-pdf-o mr-2"></i> PDF</button>
                        </div>
                    </div>
                    <div class="card-body">

                        <style>
                            .colBold {
                                font-size: 14px;
                                font-weight: 700;
                            }

                            .container {
                                width: 1123px;
                            }
                        </style>
                        <?php
                        $biaya = 0;
                        if (isset($shipping->biaya)) {
                            $biaya = $shipping->biaya;
                        }

                        $biayaLain = 0;
                        if (!empty($transaksi->biayaLain)) {
                            $biayaLain = $transaksi->biayaLain;
                        }
                        ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <table align="right" style="margin-bottom: 20px;">
                                    <tr>
                                        <td align="right" colspan="3">
                                            <h1 style="font-size: 24px; font-weight: 600">INVOICE</h1>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left"><b></b></td>
                                        <td align="center"></td>
                                        <td align="right">{{ str_replace('Kota', '', website()->webkota->name) }},
                                            {{ tglIndo($transaksi->tgl_trans) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left"><b></b></td>
                                        <td align="center"></td>
                                        <td align="right">No Transaksi: {{ $transaksi->kode_trans }}</td>
                                    </tr>
                                    <tr>
                                        <td align="left"></td>
                                        <td align="center"></td>
                                        <td align="right">Status: <?php
                                                                    $status = $transaksi->status_trans;
                                                                    if ($status == 1) {
                                                                        echo "<span class='text-danger'>MENUNGGU</span>";
                                                                    } elseif ($status == 2) {
                                                                        echo "<span class='text-danger'>PROSES</span>";
                                                                    } elseif ($status == 3) {
                                                                        echo "<span class='text-success'>SELESAI</span>";
                                                                    } elseif ($status == 4) {
                                                                        echo "<span class='text-danger'>DIBATALKAN</span>";
                                                                    } elseif ($status == 5) {
                                                                        echo "<span class='text-info'>DiBAYAR</span>";
                                                                    } elseif ($status == 6) {
                                                                        echo "<span class='text-primary'>DIKIRIM</span>";
                                                                    }
                                                                    ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-6">
                                <div class="m-2 pull-left text-left">
                                    <span class="text-muted">Dari :</span>
                                    <h5 class="my-2">{{ strtoupper(website()->nama_website) }}</h5>
                                    <ul class="list list-unstyled mb-0 text-left">
                                        <li>{{ strip_tags(website()->address) }}</li>
                                        <li>{{ website()->webkecamatan->name }}</li>
                                        <li>{{ website()->webkota->name }}</li>
                                        <li>{{ website()->webprovinsi->name }}</li>
                                        <li>{{ website()->kode_pos }}</li>
                                        <li>{{ website()->contact }}</li>
                                    </ul>
                                </div>


                            </div>
                            @if ($pelanggan)
                            <div class="col-sm-6">
                                <div class="mb-4 mb-md-2 text-left"> <span class="text-muted">Tagihan
                                        Untuk:</span>
                                    <ul class="list list-unstyled mb-0">
                                        <li>
                                            <h5 class="my-2">
                                                {{ $transaksi->pelanggan->nama_depan . ' ' . $transaksi->pelanggan->nama_belakang }}
                                            </h5>
                                        </li>
                                        <li><span class="font-weight-semibold">{{ strip_tags($transaksi->pelanggan->alamat) }}</span>
                                        </li>
                                        <li>{{ $kecamatan->name }}</li>
                                        <li>{{ $kabupaten->name }}</li>
                                        <li>{{ $provinsi->name }}</li>
                                        <li>{{ $pelanggan->kode_pos }}</li>
                                        <li>{{ $transaksi->pelanggan->telpon }}</li>
                                        <li><a href="#" data-abc="true">{{ $transaksi->pelanggan->email }}</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            @endif
                        </div>

                        @if(isset(rekening($transaksi->website_id)->nama_rek))
                        <div>
                            <p style="text-indent: 2cm">Berikut kami sampaikan permintaan pembayaran pesanan anda :</p>
                        </div>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-bordered table-stiped" border="1" width="100%" cellpadding="3" cellspacing="0">
                                <thead class="tableHeader">
                                    <tr>
                                        <td align="center" style="width: 30px;">No</td>
                                        <td width="150px" align="center">Nama Produk</td>
                                        <td align="center">Gambar</td>
                                        <td width="200px" align="center">Deskripsi</td>
                                        <td align="center">Satuan</td>
                                        <td align="center">Jumlah</td>
                                        <td align="center">Harga Satuan</td>
                                        <td align="center">Jasa Satuan</td>
                                        <td align="center">Jumlah Harga</td>
                                    </tr>
                                </thead>
                                <tbody class="tableItem">
                                    @php
                                    $totalJumlah = 0;
                                    $totalHarga = 0;
                                    $totalBiaya = 0;
                                    $totalJumlahHarga = 0;
                                    $total = 0;
                                    $grandTotal = 0;
                                    @endphp
                                    @foreach ($transaksi->order as $index => $item)
                                    <tr>
                                        <td align="center">{{ $index + 1 }}</td>
                                        <td>{{ $item->produk->nama_produk }}</td>
                                        @if (isset($pdf))
                                        <td align="center"><img src="{{ public_path('image/produk/small/' . $item->produk->gambar_utama) }}" width="60px"></td>
                                        @else
                                        <td align="center"><img src="{{ asset('image/produk/small/' . $item->produk->gambar_utama) }}" width="60px"></td>
                                        @endif
                                        <td>{!! strip_tags($item->produk->deskripsi) !!}</td>
                                        <td align="center">{{ $item->produk->satuan->name }}</td>
                                        <td align="center">{{ $item->jumlah }}</td>
                                        <td align="right">Rp.
                                            {{ number_format((float) $item->harga_jual - (float) $item->biaya, 0, ',', '.') }}
                                        </td>
                                        <td align="right">Rp.
                                            {{ number_format((float) $item->biaya, 0, ',', '.') }}
                                        </td>
                                        @php $jumlahHarga=$item->harga_jual*$item->jumlah; @endphp
                                        <td align="right">Rp. {{ number_format($jumlahHarga, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @php
                                    $totalJumlah += $item->jumlah;
                                    $totalHarga += $item->harga_jual - $item->biaya;
                                    $totalBiaya += $item->biaya;
                                    $totalJumlahHarga += $jumlahHarga;
                                    @endphp
                                    @endforeach
                                    <tr>
                                        <td colspan="2">Sub Total</td>
                                        <td colspan="3"></td>
                                        <td align="center">{{ $totalJumlah }}</td>
                                        <td align="right">Rp.
                                            {{ number_format($totalHarga, 0, ',', '.') }}
                                        </td>
                                        <td align="right">Rp.
                                            {{ number_format($totalBiaya, 0, ',', '.') }}
                                        </td>
                                        <td align="right">Rp.
                                            {{ number_format($totalJumlahHarga, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tbody>

                            </table>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                @if ($xen)
                                @if ($xen['status'] == 'PENDING')
                                <span class="text-left">Scan Tagihan</span><br><br>
                                <img src="/image/transaksi/qrbayar/{{$transaksi->kode_trans}}.svg" width="80px" alt="">
                                @endif
                                @else
                                QR Code Tidak Tersedia
                                @endif
                            </div>
                            <div class="col-md-6">
                                <table width="60%" cellpadding="4" align="right" cellspacing="0" border="1px">


                                    <tr>
                                        <td width="50%">PPN {{ website()->trx_ppn }}%</td>
                                        <td align="right">Rp.
                                            {{ number_format($transaksi->ppn, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Total</td>
                                        <td align="right">
                                            @php
                                            $total = $totalJumlahHarga + $transaksi->ppn;
                                            @endphp
                                            Rp. {{ number_format($total, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td>PPH {{ website()->trx_pph }}%</td>
                                        <td align="right">Rp. -
                                            {{ number_format($transaksi->pph, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @if ($biaya)
                                    <tr>
                                        <td>Biaya Kirim:</td>
                                        <td align="right">Rp.
                                            {{ number_format($biaya, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endif
                                    @if (!empty($biayaLain))
                                    <tr>
                                        <td>Biaya Lainnya:</td>
                                        <td align="right">Rp.
                                            {{ number_format($biayaLain, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td>Grand Total</td>
                                        <td align="right">
                                            @php
                                            $grandTotal = $total + $biaya+$biayaLain - $transaksi->pph;
                                            @endphp
                                            Rp. {{ number_format($grandTotal, 0, ',', '.') }}</td>
                                    </tr>

                                </table>
                            </div>
<div class="col-md-6"></div>
                            <div class="col-md-6">
                                @can('transaksi')

                                <?php

                                $tModal = $transaksi->totalModal + $transaksi->totalBiaya + $biayaLain + $transaksi->ppn + $biaya;

                                $pendapatan = $grandTotal - $tModal;
                                ?>

                                <table width="60%" cellpadding="4" cellspacing="0" align="right" border="1px" style="margin-top: 20px;">
                                    <tr>
                                        <td width="50%">Total Modal <small>(Modal + Biaya Jasa @if(!empty($biayaLain)) + Biaya Lain @endif @if(!empty($transaksi->ppn)) + PPN @endif @if(!empty($biaya)) + Pengiriman @endif)</small></td>
                                        <td align="right">
                                            Rp. {{ number_format($tModal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Pendapatan <small>(Grand Total - Total Modal)</small></td>
                                        <td align="right">
                                            Rp. {{ number_format($pendapatan, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        @endcan
                        @if(isset(rekening($transaksi->website_id)->nama_rek))
                        <div>
                            <p style="text-indent: 2cm">Pembayaran di transfer melalui rekening <b>{{rekening($transaksi->website_id)->nama_rek}}</b> dengan No. Rekening Bank {{rekening($transaksi->website_id)->nama_bank}} <b>{{rekening($transaksi->website_id)->no_rek}}</b>. Atas kerjasamanya kami ucapkan banyak terimakasih.</p>
                        </div>
                        @endif


                    </div>
                </div>


            </div>
        </div>
    </div>
    
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
        {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"></script> --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="{{ asset('printThis/printThis.js') }}"></script>
        <script>
            $("#btnPrint").click(function() {
                url = "{{ url('/transaksi/detail') }}" + "/{{ $transaksi->kode_trans }}" + "?print=true";
                window.location.href = url
            })

            $("#btnPdf").click(function() {
                url = "{{ url('/transaksi/detail') }}" + "/{{ $transaksi->kode_trans }}" + "?pdf=true";
                window.location.href = url
            })
        </script>
</section>
@endsection