<?php
if (!empty($export)) {
    header('Content-type: application/vnd-ms-excel');
    header('Content-Disposition: attachment; filename=laporan_stock_transfer' . date('Y-m-d') . '.xls');
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
    <title>{{ website()->nama_website }} - Laporan Transaksi Transfer Barang</title>

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
        <h3 class="text-center"><u>Laporan Transaksi Transfer Barang</u></h3>

        <table width="100%" class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>No. Nota</th>
                    <th>Dari Gudang</th>
                    <th>Ke Gudang</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                <?php $t_total=0; ?>
                @foreach ($stocktransfer as $index => $p)
                <tr>
                    <td align="center">{{ $index + 1 }}</td>
                    <td>{{tglIndo($p->tgl)}}</td>
                    <td><b>{{$p->nomor}}</b></td>
                    <td>{{$p->stock_jenis->where('jenis',2)->first()->gudang->nama}}</td>
                    <td>{{$p->stock_jenis->where('jenis',1)->first()->gudang->nama}}</td>
                    <td align="center">{{strtoupper($p->status_order)}}</td>
                    <td align="right">{{ uang($p->total) }}</td>
                </tr>
                <?php $t_total+=$p->total; ?>
                @endforeach
                <tr class="bold">
                    <td colspan="6">Total</td>
                    <td align="right">{{uang($t_total)}}</td>
                    </tr>
            </tbody>
        </table>
        <br>
        
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