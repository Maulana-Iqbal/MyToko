<?php
if (!empty($export)) {
    header('Content-type: application/vnd-ms-excel');
    header('Content-Disposition: attachment; filename=laporan_pengurangan_' . date('Y-m-d') . '.xls');
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
    <title>{{ website()->nama_website }} - Laporan Transaksi Pengurangan</title>
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
        <h3 class="text-center"><u>Laporan Transaksi Pengurangan</u></h3>

        <div class="table-responsive">
            <table id="datatable" class="table table-centered table-bordered w-100 dt-responsive">
                <thead class="table-light">
                    <tr>
                        <th style="width: 20px;">No</th>
                        <th>Tanggal</th>
                        <th>No. Nota</th>
                        <th>Sub Total</th>
                        <th>PPN</th>
                        <th>Pengiriman</th>
                        <th>Diskon (-)</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $orderTotal = 0;
                    $ppnTotal = 0;
                    $diskonTotal = 0;
                    $pengirimanTotal = 0;
                    $subTotal = 0;
                    ?>
                    @foreach($pengurangan as $index=>$p)
                    <tr>
                        <td>{{$index+1}}</td>
                        <td>{{tglIndo($p->tgl)}}</td>
                        <td><b>{{$p->nomor}}</b></td>
                        <td align="right">{{uang($p->order_total)}}</td>
                        <td align="right">{{uang($p->tax)}}</td>
                        <td align="right">{{uang($p->pengiriman)}}</td>
                        <td align="right">{{uang($p->diskon)}} (-)</td>
                        <td align="right">{{uang($p->total)}}</td>
                        <td><b>{{strtoupper($p->status_order)}}</b></td>
                    </tr>
                    <?php
                    $orderTotal += $p->order_total;
                    $ppnTotal += $p->tax;
                    $diskonTotal += $p->diskon;
                    $pengirimanTotal += $p->pengiriman;
                    $subTotal += $p->total;
                    ?>
                    @endforeach

                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <th colspan="3">Total</th>
                        <th align="right">{{uang($orderTotal)}}</th>
                        <th align="right">{{uang($ppnTotal)}}</th>
                        <th align="right">{{uang($pengirimanTotal)}}</th>
                        <th align="right">{{uang($diskonTotal)}} (-)</th>
                        <th align="right">{{uang($subTotal)}}</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <b>Total :</b> (Order Total + PPN + Pengiriman) - Diskon
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