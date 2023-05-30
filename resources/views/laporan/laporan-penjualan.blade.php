<?php
if (!empty($export)) {
    header('Content-type: application/vnd-ms-excel');
    header('Content-Disposition: attachment; filename=laporan_penjualan_' . date('Y-m-d') . '.xls');
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
    <title>{{ website()->nama_website }} - Laporan Transaksi Penjualan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <style>
        body {
            font-size: 12px;
        }

        table th {
            text-align: center;
        }

        .table tbody td,
        .table thead th {
            padding: 4px;
            font-size: 12px;
        }

        .second-table tbody td,
        .second-table thead td {
            padding: 2px;
            font-size: 10px;
        }

    </style>
</head>

<body>
    <div style="width: 98%; margin:auto;">

        @include('laporan.invoiceHeader')
        <h3 class="text-center"><u>Laporan Transaksi Penjualan</u></h3>

        <div class="table-responsive">
            <table id="datatable" class="table table-centered table-bordered w-100 dt-responsive">
                <thead class="table-light">
                    <tr>
                        <th style="width: 20px;">No</th>
                        <th>Tanggal</th>
                        <th>No. Nota</th>
                        <th>Sales</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Metode Bayar</th>
                        <th>Status Bayar</th>
                        <th>Status</th>
                        <th>Toko</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $tTotalHarga = 0;
                    $tTotalBiaya = 0;
                    $orderTotal = 0;
                    $ppnTotal = 0;
                    $pphTotal = 0;
                    $diskonTotal = 0;
                    $pengirimanTotal = 0;
                    $subTotal = 0;
                    $tBiayaLain = 0;
                    ?>
                    @foreach($penjualan as $index=>$p)
                    <tr>
                        <td align="center">{{$index+1}}</td>
                        <td align="center">{{tglIndo($p->tgl)}}</td>
                        <td align="center"><b>{{$p->nomor}}</b></td>
                        <td>{{$p->sales->nama}}</td>
                        <td>{{$p->pelanggan->nama_depan}} {{$p->pelanggan->nama_depan}}</td>
                        <td align="right"><b>{{uang($p->total)}}</b></td>
                        <td align="center">{{strtoupper($p->metode_bayar)}}</td>
                        <td align="center">
                            <?php
                            $status = '';
                            if ($p->status_bayar == 1) {
                                $status = 'Lunas';
                            } elseif ($p->status_bayar == 2) {
                                $status = 'Belum Lunas';
                            } else {
                                $status = 'Belum Bayar';
                            }
                            ?>
                            {{strtoupper($status)}}
                        </td>
                        <td align="center"><b>{{strtoupper($p->status_order)}}</b></td>
                        <td align="center">{{website($p->website_id)->nama_website}}</td>
                    </tr>
                    <tr>
                        <td colspan="10">
                            <table class="table second-table" style="min-width: 700px;">
                                <thead>
                                <tr>
                                    <td align="center">Total Harga</td>
                                    <td align="center">Total Biaya</td>
                                    <td align="center">Sub Total</td>
                                    <td align="center">PPN</td>
                                    <td align="center">PPH</td>
                                    <td align="center">Pengiriman</td>
                                    <td align="center">Biaya Lain</td>
                                    <td align="center">Diskon (-)</td>
                                    <td align="center">Total</td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td align="right">{{uang($p->total_harga)}}</td>
                                    <td align="right">{{uang($p->total_biaya)}}</td>
                                    <td align="right">{{uang($p->order_total)}}</td>
                                    <td align="right">
                                        <?php
                                        $ppnRp = 0;
                                        if ($p->ppn) {
                                            $ppnRp = ($p->total_harga / 100) * $p->ppn;
                                        }
                                        echo uang($ppnRp) . ' (' . $p->ppn . '%)';
                                        ?>
                                    </td>
                                    <td align="right">
                                        <?php
                                        $pphRp = 0;
                                        if ($p->pph) {
                                            $pphRp = ($p->total_biaya / 100) * $p->pph;
                                        }
                                        echo uang($pphRp) . ' (' . $p->pph . '%)';
                                        ?>
                                    </td>
                                    <td align="right">{{uang($p->pengiriman)}}</td>
                                    <td align="right">{{uang($p->biaya_lain)}}</td>
                                    <td align="right">{{uang($p->diskon)}} (-)</td>
                                    <td align="right">{{uang($p->total)}}</td>

                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <?php
                    $tTotalHarga += $p->total_harga;
                    $tTotalBiaya += $p->total_biaya;
                    $orderTotal += $p->order_total;
                    $ppnTotal += $ppnRp;
                    $pphTotal += $pphRp;
                    $diskonTotal += $p->diskon;
                    $pengirimanTotal += $p->pengiriman;
                    $tBiayaLain += $p->biaya_lain;
                    $subTotal += $p->total;
                    ?>
                    @endforeach

                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <th align="center" style="vertical-align: middle;">Total</th>
                        <th colspan="10">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th><b>Total Harga</b><br>{{uang($tTotalHarga)}}</th>
                                        <th><b>Total Biaya</b><br>{{uang($tTotalBiaya)}}</th>
                                        <th><b>Sub Total</b><br>{{uang($orderTotal)}}</th>
                                        <th><b>PPN</b><br>{{uang($ppnTotal)}}</th>
                                        <th><b>PPH</b><br>{{uang($pphTotal)}}</th>
                                        <th><b>Pengiriman</b><br>{{uang($pengirimanTotal)}}</th>
                                        <th><b>Biaya Lain</b><br>{{uang($tBiayaLain)}}</th>
                                        <th><b>Diskon</b><br>{{uang($diskonTotal)}} (-)</th>
                                        <th><b>Total</b><br>{{uang($subTotal)}}</th>
                                    </tr>
                                </thead>
                            </table>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <b><u>Rumus</u></b><br>
        <b>PPN :</b> (Total Harga / 100) X PPN<br>
        <b>PPN :</b> (Total Biaya / 100) X PPH<br>
        <b>Sub Total :</b> Total Harga + Total Biaya <br>
        <b>Total :</b> (Sub Total + PPN + PPH + Pengiriman + Biaya Lain) - Diskon
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
    <script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM=" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>

</html>