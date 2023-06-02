<?php
if (!empty($export)) {
    header('Content-type: application/vnd-ms-excel');
    header('Content-Disposition: attachment; filename=laporan_aset_' . $tanggal . '_kondisi_' . $kondisi . '.xls');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{  website()->nama_website }} - Laporan Aset</title>
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
        <h3 class="text-center"><u>Laporan Aset</u></h3>
        @if (!empty($tanggal))
            <b>Range Tanggal :</b> {{ $tanggal }}<br>
        @endif
        @if (!empty($kondisi))
            <b>Kondisi Aset : </b> {{ $kondisi }}<br>
        @endif
        <table id="datatable" width="100%" class="table-striped" border="1px" cellpadding="2">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Aset</th>
                    <th>Nama Aset</th>
                    <th>Merek</th>
                    <th>Satuan</th>
                    <th>Jumlah</th>
                    <th>Kondisi</th>
                    <th>Deskripsi</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($aset as $index => $p)
                    <tr>
                        <td align="center">{{ $index + 1 }}</td>
                        <td>{{ $p->kode_aset }}</td>
                        <td>{{ $p->nama_aset }}</td>
                        <td>{{ $p->merek }}</td>
                        <td>{{ $p->satuan }}</td>
                        <td>{{ $p->jumlah }}</td>
                        <td>{{ $p->kondisi }}</td>
                        <td>{{ $p->deskripsi }}</td>
                    </tr>
                @endforeach
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
