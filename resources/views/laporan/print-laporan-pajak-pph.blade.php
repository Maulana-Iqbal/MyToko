<?php
if (!empty($export)) {
    header('Content-type: application/vnd-ms-excel');
    header('Content-Disposition: attachment; filename=laporan_pajak_pph' . $tanggal .'.xls');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan PPH</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

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
      
    @include('transaksi.invoiceHeader')
        <h3 class="text-center mt-2"><u>Laporan PPH</u></h3>
        @if (!empty($tanggal))
            <b>Range Tanggal :</b> {{ $tanggal }}<br>
        @endif
        <table id="datatable" style="width: 100%;" class="table-striped" border="1px" cellpadding="2">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Transaksi</th>
                    <th>Nama Perusahaan</th>
                    <th>Tanggal Transaksi</th>
                    <th>Biaya Jasa</th>
                    <th>PPH 2%</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $biaya = 0;
                $pph = 0;
                $ppn = 0;
                $subtotal = 0;

                foreach ($transaksi as $index => $trans){
                    ?>
                <tr>
                    <td align="center">{{ $index + 1 }}</td>
                    <td>{{ $trans->kode_trans }}</td>
                    <td width="90px" align="center">{{ tglIndo($trans->tgl_trans) }}</td>
                    <td>@if($trans->pelanggan<>null){{ $trans->pelanggan->perusahaan }}@endif</td>
                    <td align="right">Rp.{{ number_format($trans->totalBiaya, 0, ',', '.') }}</td>
                    <td align="right">Rp.-{{ number_format($trans->pph, 0, ',', '.') }}</td>
                    
                </tr>
                <?php
                    $biaya += $trans->totalBiaya;
                    $pph += $trans->pph;
                    $ppn += $trans->ppn;
                    $subtotal += $trans->subtotal;
                    }
                ?>
                <tr style="background-color:white">
                    <td colspan="4" align="left"><b>Total</b></td>
                    <td align="right"><b>Rp.{{ number_format($biaya, 0, ',', '.') }}</b></td>
                    <td align="right"><b>Rp.-{{ number_format($pph, 0, ',', '.') }}</b></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
     </div>
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

</body>

</html>
