<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order {{strtoupper($data->nomor)}}</title>
    <style>
        body {
            width: 100%;
            font-size: 12px;
            margin: auto;
            padding: 0;
        }

        .container {
            width: 70vh;
            margin: auto;
            min-width: 27cm;
        }
    </style>
</head>

<body>
    <div class="container">
        <h3 style="text-align: center; padding: 0; margin: 0;">PURCHASE ORDER</h3>
        <hr>
        <br>
        <table width="100%">
            <tr>
                <td align="left" valign="top">
                    
                </td>
                <td align="right" valign="top">
                    <p class="font-13"><strong>Tanggal: </strong> &nbsp;&nbsp;&nbsp; {{tglIndo($data->tgl)}}</p>
                    <p class="font-13"><strong>Status: </strong> <span class="badge bg-success float-end">{{strtoupper($data->status_order)}}</span></p>
                    <p class="font-13"><strong>No. PO: </strong> <span class="float-end">{{$data->nomor}}</span></p>
                </td>
            </tr>
        </table>
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="40%">
                <h4>Kepada :</h4>
                    <span class="font-13"><strong>Perusahaan: </strong> {{$data->pemasok->perusahaan}}</span><br>
                    <span class="font-13"><strong>Nama: </strong> {{$data->pemasok->nama}}</span><br>
                    <span class="font-13"><strong>Alamat: </strong> {{$data->pemasok->alamat}}</span><br>
                    <span class="font-13"><strong>HP: </strong> {{$data->pemasok->telepon}}</span>
                </td>
                <td>
                    <h4>Dikirim Ke :</h4>
                <span class="font-13"><strong>Toko: </strong> {{website($data->website_id)->nama_website}}</span><br>
                    <span class="font-13"><strong>Alamat: </strong> {{website($data->website_id)->webkecamatan->name}}, {{website($data->website_id)->webkota->name}}<br>
                        {{website($data->website_id)->webprovinsi->name}}, {{website($data->website_id)->pos}}</span><br>
                    <span class="font-13"><strong>HP: </strong> {{website($data->website_id)->contact}}</span>
                </td>
            </tr>
        </table>
        <br>
        <table border="1" width="100%" align="center" cellpadding="6" cellspacing="0" style="margin: auto;">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Satuan</th>
                    <th>Jumlah</th>
                    <th>Harga Beli</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jenis as $index=>$d)
                <tr>
                    <td>{{$index+1}}</td>
                    <td>
                      {{$d->produk->nama_produk}}
                    </td>
                    <td>{{$d->produk->kategori->nama_kategori}}</td>
                    <td>{{$d->produk->satuan->name}}</td>
                    <td>{{$d->jumlah_total}}</td>
                    <td>{{uang($d->harga)}}</td>
                    <td class="text-end">
                        <?php
                        $subTotal = $d->jumlah_total * $d->harga;
                        ?>
                        {{uang($subTotal)}}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <table border="0" width="100%" align="center" cellpadding="3" cellspacing="0">
            <tr>
                <td valign="top">
                    <h4>Notes :</h4>
                    {!!$data->deskripsi!!}
                </td>
                <td valign="top">

                </td>
                <td valign="top" align="right">
                    <p><b>Sub Total : </b> <span class="float-end">{{uang($data->order_total)}}</span></p>
                    <p><b>PPN: </b> <span class="float-end">{{uang($data->tax)}}</span></p>
                    <p><b>Pengiriman : </b> <span class="float-end">{{uang($data->pengiriman)}}</span></p>
                    <p><b>Diskon : </b> <span class="float-end">{{uang($data->diskon)}} (-)</span></p>
                    <h3>{{uang($data->total)}}</h3>
                </td>
            </tr>
        </table>
        <br>
        <table border="0" width="100%" align="center" cellpadding="3" cellspacing="0">
        <tr>
            <td width="50%" align="center"></td>
            <td width="50%" align="center">
                Hormat Kami,<br><br>
                TTD
                <!-- <img src="{{url('image/website/'.website()->icon)}}" width="80px" alt=""> -->
                <br><br>
                <b>{{website()->nama_website}}</b>
            </td>
        </tr>
    </table>
    </div>
</body>

</html>