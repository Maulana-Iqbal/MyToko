<?php
if (!empty($export)) {
    header('Content-type: application/vnd-ms-excel');
    header('Content-Disposition: attachment; filename=laporan_kas_' . $tanggal . '.xls');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kas</title>
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
        <h3 class="text-center"><u>Laporan Kas</u></h3>
        @if (!empty($tanggal))
            <b>Range Tanggal :</b> {{ $tanggal }}<br>
        @endif

        @if (!empty($mutasi))
            <b>Mutasi :</b> {{ $mutasi }}<br>
        @endif

        <table id="datatable" class="table table-striped" width="100%" border="1px" cellpadding="2" cellspacing="0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Ke</th>
                    <th>Dari</th>
                    <th>Debit</th>
                    <th>Kredit</th>
                    {{-- <th>Saldo</th> --}}
                    <th>Sumber</th>
                    <th>Keterangan</th>
                    <th>Created At</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $debit = 0;
                $kredit = 0;
                $nominal = 0;
                ?>
                @foreach ($kas as $index => $p)
                    <tr>
                        <td align="center">{{ $index + 1 }}</td>
                        <td>{{ tglIndo($p->tgl) }}</td>
                        <td>@if($p->jenis==1)Kas Kecil @elseif($p->jenis==2) Kas Besar @endif</td>
                        <td>
                            <?php
                                $rekening='';
                                if($p->rekening<>null){
                                    $rekening=$p->rekening->nama_bank;
                                }
                                echo $rekening;
                                ?>
                        </td>
                        <td align="right">Rp.{{ number_format($p->debit, 0, ',', '.') }}</td>
                        <td align="right">Rp.{{ number_format($p->kredit, 0, ',', '.') }}</td>
                        {{-- <td align="right">Rp.{{ number_format($p->nominal, 0, ',', '.') }}</td> --}}
                        <td align="center">{{ $p->sumber }}</td>
                        <td>{!! strip_tags($p->deskripsi) !!}</td>
                        <td>{{ date('d-m-Y H:i:s', strtotime($p->created_at)) }}</td>
                    </tr>
                    <?php
                    $debit += $p->debit;
                    $kredit += $p->kredit;
                    ?>
                @endforeach
                <?php
                $nominal = $kredit-$debit;
                ?>
                <tr style="background-color:white">
                    <td align="left" colspan="4"><b>Total</b></td>
                    <td align="right"><b>Rp.{{ number_format($debit, 0, ',', '.') }}</b></td>
                    <td align="right"><b>Rp.{{ number_format($kredit, 0, ',', '.') }}</b></td>
                    {{-- <td align="right"><b>Rp.{{ number_format($nominal, 0, ',', '.') }}</b></td> --}}
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr style="background-color:white">
                    <td align="left" colspan="8"><b>Sisa Kas</b></td>
                    <td align="right"><b>Rp.{{ number_format($nominal, 0, ',', '.') }}</b></td>
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
