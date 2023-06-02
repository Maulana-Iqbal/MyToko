<?php
if (!empty($export)) {
    header('Content-type: application/vnd-ms-excel');
    header('Content-Disposition: attachment; filename=laporan_upah_' . $tanggal . '.xls');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Upah</title>
    @if(isset($pdf))
    <link href="{{ public_path('fe_assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    @else
    <link href="{{ asset('fe_assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    @endif
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
        <h3 class="text-center"><u>Laporan Upah</u></h3>
        @if (!empty($tanggal))
            <b>Range Tanggal :</b> {{ $tanggal }}<br>
        @endif

        @if (!empty($pegawai))
            <b>Nama :</b> {{ $pegawai }}<br>
        @endif


        @if (!empty($pekerjaan))
            <b>Pekerjaan :</b> {{ $pekerjaan }}<br>
        @endif

        @if (!empty($website))
            <b>Perusahaan :</b> {{ website($website)->nama_website }}<br>
        @endif

        <table id="datatable" class="table table-striped" width="100%" border="1px" cellpadding="2" cellspacing="0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Pekerjaan</th>
                    <th>Biaya</th>
                    <th>Jumlah</th>
                    <th>Pendapatan</th>
                    <th>Tanggal</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $subBiaya = 0;
                $subJumlah = 0;
                $subTotal = 0;
                $subPendapatan=0;
                ?>
                @foreach ($upah as $index => $p)
                    <tr>
                        <td align="center">{{ $index + 1 }}</td>
                        <td>
                            <?php
                                $pegawai='';
                                if($p->user<>null){
                                    $pegawai=$p->user->name;
                                }
                                echo $pegawai;
                                ?>
                        </td>
                        <td>
                            <?php
                                $pekerjaan='';
                                if($p->pekerjaan<>null){
                                    $pekerjaan=$p->pekerjaan->name;
                                }
                                echo $pekerjaan;
                                ?>
                        </td>
                        <td align="right">Rp.{{ number_format($p->biaya, 0, ',', '.') }}</td>
                        <td align="right">{{ $p->jumlah }}</td>
                        <td align="center">
                            <?php
                                    $pendapatan=0;
                                    $pendapatan=(double)$p->biaya*(int)$p->jumlah;
                                    echo "Rp. ".number_format($pendapatan, 0, ',', '.');
                                ?>
                        </td>
                        <td align="center">{{tglIndo(date('d-m-Y',strtotime($p->tanggal)))}}</td>
                    </tr>
                    <?php
                    $subBiaya += $p->biaya;
                    $subJumlah += $p->jumlah;
                    $subPendapatan += $pendapatan;
                    ?>
                @endforeach
                <tr style="background-color:white">
                    <td align="left" colspan="3"><b>Sub Total</b></td>
                    <td align="right"><b>Rp.{{ number_format($subBiaya, 0, ',', '.') }}</b></td>
                    <td align="right"><b>{{ $subJumlah }}</b></td>
                    <td align="right"><b>Rp.{{ number_format($subPendapatan, 0, ',', '.') }}</b></td>
                    <td></td>
                </tr>
            </tbody>
        </table>

    </div>
@if(isset($print))
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
@endif
</body>

</html>
