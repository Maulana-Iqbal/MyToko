<?php
if (!empty($export)) {
    header('Content-type: application/vnd-ms-excel');
    header('Content-Disposition: attachment; filename=laporan_stock_gudang_' . date('Y-m-d') . '.xls');
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
    <title>{{ website()->nama_website }} - Laporan Stock Gudang</title>
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
        <h3 class="text-center"><u>Laporan Stock Gudang</u></h3>

        <table width="100%" border="1px" cellpadding="2" cellspacing="0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Gudang</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Satuan</th>
                    <th>Harga Modal</th>
                    <th>Harga Jual</th>
                    <th>Harga Grosir</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $t_harga = 0;
                $t_harga_jual = 0;
                $t_harga_grosir = 0;
                $t_jumlah = 0;
                ?>
                @foreach ($stock as $index => $p)
                <tr>
                    <td align="center">{{ $index + 1 }}</td>
                    <td>{{ $p->gudang->nama }}</td>
                    <td align="center">{{ $p->kode_produk }}</td>
                    <td>{{ $p->nama_produk }}</td>
                    <td align="center">{{$p->jumlah}}</td>
                    <td align="center">{{$p->produk->satuan->name}}</td>
                    <td align="right"><b>Rp.{{ number_format($p->produk->stock->harga, 0, ',', '.') }}</b></td>
                    <td align="right"><b>Rp.{{ number_format($p->produk->stock->harga_jual, 0, ',', '.') }}</b></td>
                    <td align="right"><b>Rp.{{ number_format($p->produk->stock->harga_grosir, 0, ',', '.') }}</b></td>
                </tr>
                <?php
                $t_harga+=$p->produk->stock->harga*$p->jumlah;
                $t_harga_jual+=$p->produk->stock->harga_jual*$p->jumlah;
                $t_harga_grosir+=$p->produk->stock->harga_grosir*$p->jumlah;
                $t_jumlah+=$p->jumlah;
                ?>
                @endforeach
                <tr class="bold">
                    <td colspan="4">Total</td>
                    <td align="center">{{$t_jumlah}}</td>
                    <td></td>
                    <td align="right">{{uang($t_harga)}}</td>
                    <td align="right">{{uang($t_harga_jual)}}</td>
                    <td align="right">{{uang($t_harga_grosir)}}</td>
                </tr>
            </tbody>
        </table>
        <br>
        <?php
        $t_eceran_bersih = $t_harga_jual - $t_harga;
        $t_grosir_bersih = $t_harga_grosir- $t_harga;
        ?>
        <div class="row">
            <div class="col-6">
                <table border="1px" align="left" width="300px" cellpadding="5px" cellspacing="0">
                    <tr>
                        <td>Total Modal</td>
                        <td align="right">{{uang($t_harga)}}</td>
                    </tr>
                    <tr>
                        <td colspan="2"><b>Etimasi Pendapatan Kotor</b></td>
                    </tr>
                    <tr>
                        <td>Eceran</td>
                        <td align="right">{{uang($t_harga_jual)}}</td>
                    </tr>
                    <tr>
                        <td>Grosir</td>
                        <td align="right">{{uang($t_harga_grosir)}}</td>
                    </tr>
                </table>
            </div>
            <div class="col-6">
                <table border="1px" align="right" width="300px" cellpadding="5px" cellspacing="0">
                    <tr>
                        <td colspan="2"><b>Etimasi Pendapatan Bersih</b></td>
                    </tr>
                    <tr>
                        <td>Eceran</td>
                        <td align="right">{{uang($t_eceran_bersih)}}</td>
                    </tr>
                    <tr>
                        <td>Grosir</td>
                        <td align="right">{{uang($t_grosir_bersih)}}</td>
                    </tr>
                </table>
            </div>
        </div>
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