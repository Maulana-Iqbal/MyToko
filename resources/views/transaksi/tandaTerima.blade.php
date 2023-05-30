<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ website()->nama_website }} - Tanda Terima {{$transaksi->kode_trans}}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
    body {
        width: 90%;
        margin: 0;
        font-family: Roboto, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        font-size: .8125rem;
        font-weight: 400;
        line-height: 1.5385;
        color: #333;
        text-align: left;
        background-color: #eee
    }

    .mt-50 {
        margin-top: 50px
    }

    .mb-50 {
        margin-bottom: 50px
    }

    .card {
        position: relative;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: column;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 1px solid rgba(0, 0, 0, .125);
        border-radius: .1875rem
    }

    .card-img-actions {
        position: relative
    }

    .card-body {
        -ms-flex: 1 1 auto;
        flex: 1 1 auto;
        padding: 1.25rem;
        text-align: center
    }

    .card-title {
        margin-top: 10px;
        font-size: 17px
    }

    .invoice-color {
        color: red !important
    }

    .card-header {
        padding: .9375rem 1.25rem;
        margin-bottom: 0;
        background-color: rgba(0, 0, 0, .02);
        border-bottom: 1px solid rgba(0, 0, 0, .125)
    }

    a {
        text-decoration: none !important
    }

    .btn-light {
        color: #333;
        background-color: #fafafa;
        border-color: #ddd
    }

    .header-elements-inline {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-align: center;
        align-items: center;
        -ms-flex-pack: justify;
        justify-content: space-between;
        -ms-flex-wrap: nowrap;
        flex-wrap: nowrap
    }

    @media (min-width: 768px) {
        .wmin-md-400 {
            min-width: 400px !important
        }
    }

    .btn-primary {
        color: #fff;
        background-color: #2196f3
    }

    .btn-labeled>b {
        position: absolute;
        top: -1px;
        background-color: blue;
        display: block;
        line-height: 1;
        padding: .62503rem
    }

    body {
        font-size: 12px;
        font-family: sans-serif;
    }
    </style>



</head>

<body>
    <div class="container justify-content-center mt-50 mb-50">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-transparent header-elements-inline">
                        <h6 class="card-title">Tanda Terima Barang</h6>
                        <div class="header-elements">
                            <a target="_blank" href="/tanda-terima/{{$transaksi->kode_trans}}?pdf=true" class="btn btn-light btn-sm"><i class="fa fa-file-pdf mr-2"></i> PDF</a> 
                            <a target="_blank" href="/tanda-terima/{{$transaksi->kode_trans}}?print=true" class="btn btn-light btn-sm ml-3"><i
                                    class="fa fa-print mr-2"></i> Print</a>
                        </div>
                    </div>
                    <div class="print">
                  @include('transaksi.invoiceHeader')
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    {{-- <div class="mb-4 pull-left text-left">
                                       <table width="50%" class="mt-2">
                                           <tr><td width="50%">Nomor </td><td>: </td><td>{{$transaksi->kode_trans}}</td></tr>
                                           <tr><td valign="top">Perihal </td><td valign="top">: </td><td>Tanda Terima Barang</td></tr>
                                       </table>
                                    </div> --}}
                                </div>
                                <div class="col-sm-6">
                                    <table align="right" style="margin-bottom: 20px;">
                                        <tr>
                                            <td align="right" colspan="3">
                                                <h1 style="font-size: 24px; font-weight: 600; margin-bottom: 0; padding-bottom: 0;">TANDA TERIMA</h1>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="left"><b></b></td><td align="center"></td><td align="right">{{str_replace('Kota','',website()->webkota->name)}}, {{ tglIndo($transaksi->tgl_trans) }}</td>
                                        </tr>
                                        <tr>
                                            <td align="left"><b></b></td><td align="center"></td><td align="right">No Transaksi: {{ $transaksi->kode_trans }}</td>
                                        </tr>
                                        
                                    </table>
                                </div>
                            </div>

                        </div>

                        <div class="container">
                        <div class="row">
                                <div class="col-sm-12">
                                    Yang bertanda tangan dibawah ini :<br><br>
                                    <table width="100%">
                                        <tr>
                                            <td width="120px">Nama Lengkap</td>
                                            <td>:</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>Jabatan</td>
                                            <td>:</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>Alamat</td>
                                            <td>:</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>No. HP / Telpon</td>
                                            <td>:</td>
                                            <td></td>
                                        </tr>
                                    </table><br>
                                    Telah menerima barang dibawah ini :<br><br>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered" border="1" width="100%" cellpadding="3" cellspacing="0">
                                    <thead class="tableHeader">
                                    <tr>
                                        <td align="center">No</td>
                                        <td align="center">Nama Produk</td>
                                        <td align="center">Gambar</td>
                                        <td align="center">Deskripsi</td>
                                        <td align="center">Satuan</td>
                                        <td align="center">Jumlah</td>
                                    </tr>
                                </thead>
                                <tbody class="tableItem">
                                    @php
                                    $totalJumlah=0;
                                    $totalHarga=0;
                                    $totalBiaya=0;
                                    $totalJumlahHarga=0;
                                    $total=0;
                                    $grandTotal=0;
                                    @endphp
                                    @foreach($transaksi->order as $index=>$item)
                                    <tr>
                                        <td align="center">{{$index+1}}</td>
                                        <td>{{$item->stock->produk->nama_produk}}</td>
                                        @if(isset($pdf))
                                        <td align="center"><img src="{{public_path('image/produk/small/'.$item->stock->produk->gambar_utama)}}" width="60px"></td>
                                        @else
                                        <td align="center"><img src="{{asset('image/produk/small/'.$item->stock->produk->gambar_utama)}}" width="60px"></td>
                                        @endif
                                        <td>{!!strip_tags($item->stock->produk->deskripsi)!!}</td>
                                        <td align="center">{{$item->stock->satuan->name}}</td>
                                        <td align="center">{{$item->jumlah}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-body">
                           <div class="row">
                               <div class="col-sm-6" style="text-align: center;">
                                    Yang Menyerahkan,<br>
                                    <div style="margin-top: 10px; margin-bottom:10px;">
                                    <img src="{{asset('image/website/'.website()->icon)}}" width="80px" alt="">
                                    </div>
                                   CV. {{website()->nama_website}}
                               </div>

                               <div class="col-sm-6">
                                   Yang Menerima,<br><br><br><br><br>
                                   ..................................
                               </div>
                           </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <span>THANK YOU FOR YOUR ORDER</span><br>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    {{-- <script src="{{asset('printThis/printThis.js')}}"></script> --}}
    
</body>

</html>
