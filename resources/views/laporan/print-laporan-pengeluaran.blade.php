<?php
if (!empty($export)) {
    header('Content-type: application/vnd-ms-excel');
    header('Content-Disposition: attachment; filename=laporan_pengeluaran_' . $tanggal . '_status_' . $persetujuan . '.xls');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pengeluaran</title>
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
        <h3 style="text-align:center"><u>Laporan Pengeluaran</u></h3>
        @if (!empty($tanggal))
            <b>Range Tanggal :</b> {{ $tanggal }}<br>
        @endif


        @if (!empty($persetujuan))
            <b>Status Pengeluaran : </b> {{ $persetujuan }}<br>
        @endif

        <table id="datatable" width="100%" class="table-striped" border="1px" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nomor/Nota/Invoice</th>
                    <th>Tanggal</th>
                    <th>Akun</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Deskripsi</th>
                    <th>Lampiran</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $biaya = 0;
                ?>
                @foreach ($pengeluaran as $index => $p)
                    <tr>
                        <td align="center">{{ $index + 1 }}</td>
                        <td>{{ $p->nomor }}</td>
                        <td align="center">{{ tglIndo($p->tgl) }}</td>
                        <td>{{$p->akun->kode.' - '.$p->akun->name}}</td>
                        <td align="right">Rp.{{ number_format($p->biaya, 0, ',', '.') }}</td>
                        <td align="center">
                            @if ($p->persetujuan == 1)
                                Belum Verifikasi
                            @elseif($p->persetujuan == 2)
                                <b>Disetujui</b>
                            @elseif($p->persetujuan == 3)
                                Ditolak
                            @endif
                        </td>
                        <td width="200px">{!! strip_tags($p->deskripsi) !!}</td>
                        <td width="150px">
                            {{asset('image/pengeluaran/'.$p->file)}}

                        </td>
                    </tr>
                    <?php
                    $biaya += $p->biaya;
                    ?>
                @endforeach
                <tr style="background-color:white">
                    <td colspan="4" align="left"><b>Total</b></td>
                    <td align="right"><b>Rp.{{ number_format($biaya, 0, ',', '.') }}</b></td>
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

if (style.styleSheet){
  style.styleSheet.cssText = css;
} else {
  style.appendChild(document.createTextNode(css));
}

head.appendChild(style);

window.print();
    </script>

</body>

</html>
