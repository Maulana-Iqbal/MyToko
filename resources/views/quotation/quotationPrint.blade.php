<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penawaran {{strtoupper($quotation->no_quo)}}</title>
    <style>
        body{
            width: 100%;
            font-size: 12px;
            margin: auto;
            padding: 0;
        }
        .container{
            width: 70vh;
            margin: auto;
            min-width: 27cm;
        }
    </style>
</head>

<body>
    <div class="container">
        <h3 style="text-align: center; padding: 0; margin: 0;">PENAWARAN</h3>
        <hr>
    <table width="100%">
        <tr>
            <td align="left" valign="top">
                <p class="font-13"><strong>Toko: </strong> {{website($transaksi->website_id)->nama_website}}</p>
                <p class="font-13"><strong>Alamat: </strong> {{website($transaksi->website_id)->webkecamatan->name}}, {{website($transaksi->website_id)->webkota->name}}<br>
                    {{website($transaksi->website_id)->webprovinsi->name}}, {{website($transaksi->website_id)->pos}}
                </p>
                <p class="font-13"><strong>HP: </strong> {{website($transaksi->website_id)->contact}}</p>
            </td>
            <td align="right" valign="top">
                <p class="font-13"><strong>Tanggal Dikeluarkan: </strong> &nbsp;&nbsp;&nbsp; {{tglIndo($quotation->tgl_dikeluarkan)}}</p>
                <p class="font-13"><strong>No. Penawaran: </strong> {{strtoupper($quotation->no_quo)}}</p>
                <p class="font-13"><strong>Berlaku Hingga: </strong> {{tglIndo($quotation->tgl_kedaluwarsa)}}</p>
                <p class="font-13"><strong>Status: </strong>
                    @if($quotation->status==0)
                    Draft
                    @elseif($quotation->status==1)
                    Dikirim
                    @elseif($quotation->status==2)
                    Disetujui
                    @elseif($quotation->status==3)
                    Ditolak
                    @elseif($quotation->status==4)
                    Kedaluwarsa
                    @endif
                </p>
            </td>
        </tr>
    </table>
    <table width="400px" cellpadding="0" cellspacing="0">
        <tr>
            <td>Kepada Yth,</td>
        </tr>
        <tr>
            <td>{{$quotation->kepada}}</td>
        </tr>
        <tr>
            <td>Di</td>
        </tr>
        <tr>
            <td>{{$quotation->di}}</td>
        </tr>
    </table>
    {!!$quotation->pembuka!!}
    <table border="1" width="94%" align="center" cellpadding="6" cellspacing="0" style="margin: auto;">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Satuan</th>
                <th>Harga</th>
                <th>Biaya Jasa</th>
                <th>Harga + Biaya Jasa</th>
                <th>Jumlah</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $tHarga = 0;
            $tBiaya = 0;
            $tHargaFinal = 0;
            $tJumlah = 0;
            $tTotal = 0;
            ?>
            @foreach($transaksi->stock_jenis as $index=>$d)
            <tr>
                <td align="center">{{$index+1}}</td>
                <td>
                    {{$d->produk->nama_produk}}
                </td>
                <td align="center">{{$d->produk->satuan->name}}</td>
                <td align="right">{{uang($d->harga_final-$d->biaya)}}</td>
                <td align="right">{{uang($d->biaya)}}</td>
                <td align="right">{{uang($d->harga_final)}}</td>
                <td align="center">{{$d->jumlah}}</td>
                <td align="right">
                    <?php
                    $subTotal = $d->jumlah * $d->harga_final;
                    ?>
                    {{uang($subTotal)}}
                </td>
            </tr>
            <?php
            $tHarga += ($d->harga_final - $d->biaya) * $d->jumlah;
            $tBiaya += $d->biaya * $d->jumlah;
            $tHargaFinal += $d->harga_final * $d->jumlah;
            $tJumlah += $d->jumlah;
            $tTotal += $subTotal;
            ?>
            @endforeach

        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">Sub Total</td>
                <td align="right">{{uang($tHarga)}}</td>
                <td align="right">{{uang($tBiaya)}}</td>
                <td align="right">{{uang($tHargaFinal)}}</td>
                <td align="center">{{$tJumlah}}</td>
                <td align="right">{{uang($tTotal)}}</td>
            </tr>
        </tfoot>
    </table>
    <table border="0" width="100%" align="center" cellpadding="3" cellspacing="0">
        <tr>
            <td valign="top"></td>
            <td align="right" valign="top">
                <p><b>Sub Total : </b> <span class="float-end">{{uang($transaksi->order_total)}}</span></p>
                <p><b>PPN: </b> <span class="float-end">
                        <?php
                        $ppnRp = ($transaksi->total_harga / 100) * $transaksi->ppn;
                        echo uang($ppnRp);
                        ?>
                        ({{$transaksi->ppn}}%)
                    </span></p>
                <p><b>PPH: </b> <span class="float-end">
                        <?php
                        $pphRp = ($transaksi->total_biaya / 100) * $transaksi->pph;
                        echo uang($pphRp);
                        ?>
                        ({{$transaksi->pph}}%) (-)
                    </span></p>
            </td>
            <td align="right" valign="top">
                <p><b>Pengiriman : </b> <span class="float-end">{{uang($transaksi->pengiriman)}}</span></p>
                <p><b>Biaya Lain : </b> <span class="float-end">{{uang($transaksi->biaya_lain)}}</span></p>
                <p><b>Diskon : </b> <span class="float-end">{{uang($transaksi->diskon)}} (-)</span></p>
                <h3>Grand Total : {{uang($transaksi->total)}}</h3>
            </td>
        </tr>
    </table>



    {!!$quotation->penutup!!}
    <table border="0" width="100%" align="center" cellpadding="3" cellspacing="0">
        <tr>
            <td width="50%" valign="top">
            {!!$quotation->catatan!!}
            </td>
            <td width="50%" align="center">
                Hormat Kami,<br><br>
                TTD
                <!-- <img src="{{url('image/website/'.website()->icon)}}" width="80px" alt=""> -->
                <br><br>
                {{website()->nama_website}}
            </td>
        </tr>
    </table>
    </div>
</body>

</html>