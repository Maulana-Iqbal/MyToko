<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }} - INVOICE {{$transaksi->kode_trans}}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
    body {
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

    #info td{
        padding: 3px;
    }
    #info th{
        padding: 3px;
    }
    td p,h5,h6{
        margin: 0;
    }
    </style>



</head>

<body>
    <div class="container justify-content-center mt-50 mb-50">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-transparent header-elements-inline">
                        <h6 class="card-title">Faktur Penjualan</h6>
                        <div class="header-elements">
                            <!-- <button type="button" class="btn btn-light btn-sm"><i class="fa fa-file mr-2"></i> Save</button> -->
                            <button type="button" id="btnPrint" class="btn btn-light btn-sm ml-3"><i
                                    class="fa fa-print mr-2"></i> Print</button>
                        </div>
                    </div>
                    <div class="print">
                       @include('transaksi.invoiceHeader')
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-4 pull-left text-left">
                                        <span class="text-muted">Dari :</span>
                                        <h5 class="my-2">{{ config('app.name', 'Laravel') }}</h5>
                                        <ul class="list list-unstyled mb-0 text-left">
                                            <li>{{strip_tags(website()->address)}}, {{website()->webkecamatan->name}}, {{website()->kode_pos}}</li>
                                            <li>{{website()->webkota->name}}</li>
                                            <li>{{website()->webprovinsi->name}}</li>
                                            <li>{{website()->contact}}</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-4 ">
                                        <div class="text-sm-right">
                                            <h4 class="mb-2 mt-md-2">{{$transaksi->kode_trans}}
                                            </h4>
                                            <ul class="list list-unstyled mb-0">
                                                <li><span
                                                        class="font-weight-semibold">{{tglIndo($transaksi->tgl_trans)}}</span>
                                                </li>
                                                <!-- <li>Due date: <span class="font-weight-semibold">March 30, 2020</span></li> -->
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">

                                    <div class="mb-4 mb-md-2 text-left"> <span class="text-muted">Tagihan
                                            Untuk:</span>
                                        <ul class="list list-unstyled mb-0">
                                            <li>
                                                <h5 class="my-2">{{$transaksi->pelanggan->nama_lengkap}}</h5>
                                            </li>
                                            <li><span
                                                    class="font-weight-semibold">{{$transaksi->pelanggan->alamat}}</span>
                                            </li>
                                            <li>{{$desa->name.', '.$kecamatan->name.', '.$kabupaten->name}}</li>
                                            <li>{{$provinsi->name}}</li>
                                            <li>{{$pelanggan->kode_pos}}</li>
                                            <li>{{$transaksi->pelanggan->telpon}}</li>
                                            <li><a href="#" data-abc="true">{{$transaksi->pelanggan->email}}</a>
                                            </li>
                                        </ul>
                                    </div>

                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-2 ml-auto text-left"> <span class="text-muted">Detail
                                            Pembayaran:</span>
                                        <!-- <div class="d-flex flex-wrap wmin-md-400">
                                                <ul class="list list-unstyled mb-0 text-left">
                                                    <li>
                                                        <h5 class="my-2">Total :</h5>
                                                    </li>
                                                    <li>Nama Bank:</li>
                                                    <li>No. Rek :</li>
                                                    <li>KCP:</li>
                                                </ul>
                                                <ul class="list list-unstyled text-right mb-0 ml-auto">
                                                    <li>
                                                        <h5 class="font-weight-semibold my-2">Rp.
                                                            {{number_format($transaksi->total,0,',','.')}}</h5>
                                                    </li>
                                                    <li><span class="font-weight-semibold"></span></li>
                                                    <li>Mandiri</li>
                                                    <li>123456789</li>
                                                    <li>Bukittinggi</li>
                                                </ul>
                                            </div> -->
                                        <table width="100%">
                                            <tr>
                                                <td width="40%">
                                                    <h5 class="my-2">Total Bayar </h5>
                                                </td>
                                                <td>:</td>
                                                <td>
                                                    <h5 class="font-weight-semibold my-2">Rp.
                                                        {{number_format($transaksi->total,0,',','.')}}</h5>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Nama Bank</td>
                                                <td>:</td>
                                                <td>Mandiri</td>
                                            </tr>
                                            <tr>
                                                <td>No. Rek</td>
                                                <td>:</td>
                                                <td>123456789456<br>a/n Abcdefghijklmn</td>
                                            </tr>
                                            <tr>
                                                <td>KCP</td>
                                                <td>:</td>
                                                <td>Aur Bukittinggi</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="container">
                            <div class="table-responsive">
                                <table class="table table-striped table-lg">
                                    <thead>
                                        <tr>
                                            <th>Kode Produk</th>
                                            <th>Nama Produk</th>
                                            <th>Harga</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order as $order)
                                        <tr>

                                            <td>{{$order->produk->kode_produk}}</td>
                                            <td>
                                                {{$order->produk->nama_produk}}
                                            </td>
                                            <td>Rp. {{number_format($order->harga_jual,0,',','.')}}</td>
                                            <td><span class="font-weight-semibold">{{$order->jumlah}}</span></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">

                                <div class="col-sm-8">
                                    <div class="pt-2 mb-3 wmin-md-400 ml-auto">
                                        <!-- <h6 class="mb-3 text-left">Total</h6> -->
                                        <div class="table-responsive">
                                            <table id="info" class="table" width="100%">
                                                <tbody>
                                                    <tr>
                                                        <th class="text-left">Subtotal:</th>
                                                        <td class="text-right">Rp.
                                                            {{number_format($transaksi->subtotal,0,',','.')}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-left">Tax PPN: <span
                                                                class="font-weight-normal">10%)</span></th>
                                                        <td class="text-right">Rp.
                                                            {{number_format($transaksi->ppn,0,',','.')}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-left">
                                                            <h6 class="font-weight-semibold">Total:</h6>
                                                        </th>
                                                        <td class="text-right text-primary">
                                                            <h6 class="font-weight-semibold">
                                                                <?php
                                                            $pph=$transaksi->pph;
                                                            $total=$transaksi->total;
                                                            $gtotal=$total+$pph;
                                                        ?>
                                                                Rp.
                                                                {{number_format($gtotal,0,',','.')}}</h6>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-left">Tax PPH: <span
                                                                class="font-weight-normal">2%)</span></th>
                                                        <td class="text-right">Rp.
                                                            -{{number_format($transaksi->pph,0,',','.')}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-left">Total Diterima:</th>
                                                        <td class="text-right text-primary">
                                                            <h5 class="font-weight-semibold">Rp.
                                                                {{number_format($transaksi->total,0,',','.')}}</h5>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-left">Status Pembayaran:</th>
                                                        <td class="text-right text-primary">
                                                            <?php
                                                                    $status=$transaksi->status_trans;
                                                                    if($status==1){
                                                                        echo "<p class='text-danger'>Belum Dibayar</p>";
                                                                    }elseif($status==2){
                                                                        echo "<p class='text-danger'>Belum Dibayar</p>";
                                                                    }elseif($status==3){
                                                                        echo "<p class='text-success'>Telah Dibayar</p>";
                                                                    }elseif($status==4){
                                                                        echo "<p class='text-danger'>Belum Dibayar</p>";
                                                                    }
                                                                ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-left">Status Transaksi:</th>
                                                        <td class="text-right">
                                                            <?php
                                                                    $status=$transaksi->status_trans;
                                                                    if($status==1){
                                                                        echo "<p class='text-danger'>Menunggu Verifikasi</p>";
                                                                    }elseif($status==2){
                                                                        echo "<p class='text-danger'>Proses</p>";
                                                                    }elseif($status==3){
                                                                        echo "<p class='text-success'>Selesai</p>";
                                                                    }elseif($status==4){
                                                                        echo "<p class='text-danger'>Dibatalkan</p>";
                                                                    }
                                                                ?>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">

                                    <span class="text-left">Scan Halaman Invoice</span><br><br>
                                    {!! QrCode::size(80)->generate(url('invoice').'/'.$transaksi->id) !!}

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
    <script src="{{asset('printThis/printThis.js')}}"></script>
    <script>
    $("#btnPrint").click(function() {
        url = "{{url('/invoicePrint')}}" + "/{{$transaksi->id}}"
        window.location.href = url
    })
    </script>
</body>

</html>
