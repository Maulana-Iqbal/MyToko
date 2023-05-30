<?php
if (!empty($export)) {
    header('Content-type: application/vnd-ms-excel');
    header('Content-Disposition: attachment; filename=laporan_mutasi_stock_' . date('Y-m-d') . '.xls');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ website()->nama_website }} - Laporan Riwayat Stock</title>
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"> -->
    <style>
        body {
            font-size: 12px;
        }

        table th {
            text-align: center;
        }
        .bold td{
            font-weight: 800;
        }
    </style>
</head>

<body>
    <div style="width: 98%; margin:auto;">

        @include('transaksi.invoiceHeader')
        <h3 class="text-center"><u>Laporan Riwayat Stock</u></h3>
        @if (!empty($tanggal))
        <b>Range Tanggal :</b> {{ $tanggal }}<br>
        @endif

        @if (!empty($jenis))
        <b>Jenis :</b> {{ $jenis }}<br>
        @endif

        <table width="100%" border="1px" cellpadding="2" cellspacing="0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Jenis</th>
                    <th>Gudang</th>
                    <th>Jumlah</th>
                    <th>Satuan</th>
                    <th>Stock Awal</th>
                    <th>Stock Akhir</th>
                    <th>Harga Modal Lama</th>
                    <th>Harga Jual Lama</th>
                    <th>Harga Grosir Lama</th>
                    <th>Harga Modal</th>
                    <th>Harga Jual Eceran</th>
                    <th>Harga Grosir</th>
                    <!-- <th>Status</th> -->
                    <th>Deskripsi</th>
                </tr>
            </thead>

            <tbody>
              
                @foreach ($historyStock as $index => $p)
                <tr>
                    <td align="center">{{ $index + 1 }}</td>
                    <td>{{ tglIndo($p->tgl) }}</td>
                    <td>{{ $p->produk->kode_produk }}</td>
                    <td>{{ $p->produk->nama_produk }}</td>
                    <td align="center">
                        <?php
                        $jenis = '';
                        if ($p->jenis == 1) {
                            $jenis = '<span class="text-success">Tambah</span>';
                        } elseif ($p->jenis == 2) {
                            $jenis = '<span class="text-primary">Jual</span>';
                        } elseif ($p->jenis == 3) {
                            $jenis = '<span class="text-info">Pengurangan</span>';
                        } elseif ($p->jenis == 4) {
                            $jenis = '<span class="text-info">Ubah Harga</span>';
                        } elseif ($p->jenis == 5) {
                            $jenis = '<span class="text-info">Transfer Dari</span>';
                        } elseif ($p->jenis == 6) {
                            $jenis = '<span class="text-info">Transfer Ke</span>';
                        }
                        echo $jenis;
                        ?>
                    </td>
                    <td>@if($p->gudang){{$p->gudang->nama}}@endif</td>
                    <td align="center">
                        <?php
                        $jumlah = 0;
                        if ($p->jenis == 1) {
                            $jumlah = '<span class="text-success">+' . $p->jumlah . '</span> ';
                        } elseif ($p->jenis == 2) {
                            $jumlah = '<span class="text-primary">-' . $p->jumlah . '</span> ';
                        } elseif ($p->jenis == 3) {
                            $jumlah = '<span class="text-info">-' . $p->jumlah . '</span> ';
                        } elseif ($p->jenis == 4) {
                            $jumlah = '<span class="text-info">' . $p->jumlah . '</span> ';
                        }
                        echo $jumlah;
                        ?>
                    </td>
                    <td align="center">{{$p->produk->satuan->name}}</td>
                    <td align="center">{{ $p->stock_awal }}</td>
                    <td align="center">
                        <?php
                        $stockAkhir = 0;
                        if ($p->jenis == 1) {
                            $stockAkhir = (int) $p->stock_awal + $p->jumlah;
                        } elseif ($p->jenis == 2) {
                            $stockAkhir = (int) $p->stock_awal - $p->jumlah;
                        } elseif ($p->jenis == 3) {
                            $stockAkhir = (int) $p->stock_awal - $p->jumlah;
                        } elseif ($p->jenis == 4) {
                            $stockAkhir = (int) $p->stock_awal;
                        }
                        echo $stockAkhir;
                        ?>
                    </td>
                    <td align="right"><del><b>Rp.{{ number_format($p->harga_modal_awal, 0, ',', '.') }}</b></del></td>
                    <td align="right"><del><b>Rp.{{ number_format($p->harga_jual_awal, 0, ',', '.') }}</b></del></td>
                    <td align="right"><del><b>Rp.{{ number_format($p->harga_grosir_awal, 0, ',', '.') }}</b></del></td>
                    <td align="right"><b>Rp.{{ number_format($p->harga, 0, ',', '.') }}</b></td>
                    <td align="right"><b>Rp.{{ number_format($p->harga_jual, 0, ',', '.') }}</b></td>
                    <td align="right"><b>Rp.{{ number_format($p->harga_grosir, 0, ',', '.') }}</b></td>
                    <!-- <td>@if($p->verifikasi==0) <span class="text-info">BELUM VERIFIKASI</span> @elseif($p->verifikasi==1) <span class="text-success">DISETUJUI</span> @elseif($p->verifikasi==2) <span class="text-danger">DITOLAK</span> @endif</td> -->
                    {{-- <td>{{ date('d-m-Y', strtotime($p->created_at)) }}</td> --}}
                    <td>{!!$p->deskripsi!!}</td>
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