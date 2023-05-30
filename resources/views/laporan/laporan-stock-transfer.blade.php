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
    <title>{{ website()->nama_website }} - Laporan Transfer Barang</title>

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
        <h3 class="text-center"><u>Laporan Transfer Barang</u></h3>

        <table width="100%" class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Dari Gudang</th>
                    <th>Ke Gudang</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Satuan</th>
                    <th>Stock Awal</th>
                    <th>Jumlah</th>
                    <th>Stock Akhir</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $t_jumlah = 0;
                $t_jumlah_awal=0;
                $t_jumlah_akhir=0;
                ?>
                @foreach ($stock as $index => $p)
                <tr>
                    <td align="center">{{ $index + 1 }}</td>
                    <td>{{tglIndo($p->tgl)}}</td>
                    <td>{{$p->dari_gudang->nama}}</td>
                    <td>{{$p->ke_gudang->nama}}</td>
                    <td align="center">{{ $p->kode_produk }}</td>
                    <td>{{ $p->produk->nama_produk }}</td>
                    <td align="center">{{$p->produk->satuan->name}}</td>
                    <td align="center">{{$p->stock_awal}}</td>
                    <td align="center">{{$p->jumlah}}</td>
                    <td align="center">
                    <?php
                    if($p->jenis==1){
                        $akhir=$p->stock_awal+$p->jumlah;
                    }elseif($p->jenis==2){
                        $akhir=$p->stock_awal-$p->jumlah;
                    }
                    ?>
                    {{$akhir}}
                    </td>
                </tr>
                <?php
                $t_jumlah += $p->jumlah;
                $t_jumlah_awal+=$p->stock_awal;
                $t_jumlah_akhir+=$akhir;
                ?>
                @endforeach
                <tr class="bold">
                    <td colspan="7">Total</td>
                    <td align="center">{{$t_jumlah_awal}}</td>
                    <td align="center">{{$t_jumlah}}</td>
                    <td align="center">{{$t_jumlah_akhir}}</td>
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