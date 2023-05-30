<?php
if (!empty($export)) {
    header('Content-type: application/vnd-ms-excel');
    header('Content-Disposition: attachment; filename=laporan_stock_' . date('Y-m-d') . '.xls');
}
$bs_url = url('css/bs/bootstrap.min.css');
if (isset($pdf)) {
    $bs_url = public_path() . '/css/bs/bootstrap.min.css';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ website()->nama_website }} - Laporan Barang Keluar</title>
    <link rel="stylesheet" href="{{$bs_url}}">
    <style>
        body {
            font-size: 12px;
        }

        table th {
            text-align: center;
        }
    </style>
</head>

<body>
    <div style="width: 98%; margin:auto;">

        @include('laporan.invoiceHeader')
        <h3 class="text-center"><u>Laporan Barang Keluar</u></h3>

        <table width="100%" class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Gudang</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Satuan</th>
                    <th>Stock Awal</th>
                    <th>Jumlah Keluar</th>
                    <th>Stock Akhir</th>
                    <th>Harga Modal</th>
                    <th>Harga Jual</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $t_harga_modal = 0;
                $t_harga_jual = 0;
                $t_stock_awal = 0;
                $t_jumlah = 0;
                $t_stock_akhir = 0;
                ?>
                @foreach ($stock as $index => $p)
                <tr>
                    <td align="center">{{ $index + 1 }}</td>
                    <td>{{tglIndo($p->tgl)}}</td>
                    <td>@if($p->gudang) {{$p->gudang->nama}} @else Gudang Umum @endif</td>
                    <td align="center">{{ $p->produk->kode_produk }}</td>
                    <td>{{ $p->produk->nama_produk }}</td>
                    <td align="center">{{$p->produk->satuan->name}}</td>
                    <td align="center">{{$p->stock_awal}}</td>
                    <td align="center">{{$p->jumlah}}</td>
                    <td align="center">{{$p->stock_awal-$p->jumlah}}</td>
                    <td align="right">{{uang($p->harga)}}</td>
                    <td align="right">{{uang($p->harga_final)}}</td>
                </tr>
                <?php
                $t_harga_modal += $p->harga * $p->jumlah;
                $t_harga_jual += $p->harga_final * $p->jumlah;
                $t_stock_awal += $p->stock_awal;
                $t_jumlah += $p->jumlah;
                $t_stock_akhir += $p->stock_awal - $p->jumlah;
                ?>
                @endforeach
                <tr class="bold">
                    <td colspan="6">Total</td>
                    <td align="center">{{$t_stock_awal}}</td>
                    <td align="center">{{$t_jumlah}}</td>
                    <td align="center">{{$t_stock_akhir}}</td>
                    <td align="right">{{uang($t_harga_modal)}}</td>
                    <td align="right">{{uang($t_harga_jual)}}</td>
                </tr>


            </tbody>
        </table>
        <br>
        <?php
        $t_modal = $t_harga_modal;
        $t_pendapatan_kotor = $t_harga_jual;
        $t_pendapatan_bersih = $t_pendapatan_kotor - $t_modal;
        ?>
        <table border="1px" width="300px" cellpadding="5px" cellspacing="0">
            <tr>
                <td><b>Total Modal</b></td>
                <td align="right">{{uang($t_modal)}}</td>
            </tr>
            <tr>
                <td><b>Pendapatan Kotor</b></td>
                <td align="right">{{uang($t_pendapatan_kotor)}}</td>
            </tr>
            <tr>
                <td><b>Pendapatan Bersih</b></td>
                <td align="right">{{uang($t_pendapatan_bersih)}}</td>
            </tr>
        </table>
    </div>
    @if(isset($print))
    <script>
        var css = '@page { size: landscape; }',
            head = document.head || document.getElementsByTagName('head')[0],
            style = document.createElement('style');

        style.type = 'text/css';
        style.media = 'print';

        if (style.styleSheet) {
            style.styleSheet.cssText = css;
        } else {
            style.appendChild(document.createTextNode(css));
        }

        head.appendChild(style);

        window.print();
    </script>
    @endif

</body>

</html>