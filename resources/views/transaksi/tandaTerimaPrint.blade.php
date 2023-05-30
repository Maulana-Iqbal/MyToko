<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tanda Terima {{$transaksi->kode_trans}}</title>
    <style>
        body{
            font-size: 12px;
        }
        ul{
            padding: 0;
            margin:0;
        }
        ul li {
            list-style-type: none;
        }
        .tableHeader{
            font-size: 12px;
            font-weight: 600;
            text-align: center;
        }
        .tableItem{
            font-size: 12px;
        }
        
    </style>
</head>

<body>
    
                    <div class="print">
                  @include('transaksi.invoiceHeader')
                                <table align="right" style="margin-bottom: 20px;">
                                        <tr>
                                            <td align="right" colspan="3">
                                                <h1 style="font-size: 24px; font-weight: 600; margin-bottom: 0; padding-bottom: 0;">TANDA TERIMA</h1>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="left"><b></b></td><td align="center"></td><td align="right">{{str_replace('Kota','',website()->webkota->name)}}, {{ tglIndo($transaksi->tgl_trans) }}</td>
                                        </tr>
                                        <tr>
                                            <td align="left"><b></b></td><td align="center"></td><td align="right">No Transaksi: {{ $transaksi->kode_trans }}</td>
                                        </tr>
                                        
                                    </table>
                         
                                    <table width="100%">
                                        <tr>
                                            <td colspan="3">
                                                Yang bertanda tangan dibawah ini :<br><br>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="120px">Nama Lengkap</td>
                                            <td>:</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>Jabatan</td>
                                            <td>:</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>Alamat</td>
                                            <td>:</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>No. HP / Telpon</td>
                                            <td>:</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <br><br>Telah menerima barang dibawah ini :
                                            </td>
                                        </tr>
                                    </table><br>
                                    
              
                                    <table border="1" width="100%" cellpadding="3" cellspacing="0">
                                    <thead class="tableHeader">
                                    <tr>
                                        <td align="center">No</td>
                                        <td align="center">Nama Produk</td>
                                        <td align="center">Gambar</td>
                                        <td align="center">Deskripsi</td>
                                        <td align="center">Satuan</td>
                                        <td align="center">Jumlah</td>
                                    </tr>
                                </thead>
                                <tbody class="tableItem">
                                    @php
                                    $totalJumlah=0;
                                    $totalHarga=0;
                                    $totalBiaya=0;
                                    $totalJumlahHarga=0;
                                    $total=0;
                                    $grandTotal=0;
                                    @endphp
                                    @foreach($transaksi->order as $index=>$item)
                                    <tr>
                                        <td align="center">{{$index+1}}</td>
                                        <td>{{$item->stock->produk->nama_produk}}</td>
                                        @if(isset($pdf))
                                        <td align="center"><img src="{{public_path('image/produk/small/'.$item->stock->produk->gambar_utama)}}" width="60px"></td>
                                        @else
                                        <td align="center"><img src="{{asset('image/produk/small/'.$item->stock->produk->gambar_utama)}}" width="60px"></td>
                                        @endif
                                        <td width="250px">{!!strip_tags($item->stock->produk->deskripsi)!!}</td>
                                        <td align="center">{{$item->stock->satuan->name}}</td>
                                        <td align="center">{{$item->jumlah}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                </table>

                                <br><br>
                                <table width="100%" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="50%" align="center" valign="top">
                                            Yang Menyerahkan,<br>
                                        @if(isset($pdf))
                                    <img src="{{public_path('image/website/'.website()->icon)}}" width="80px" style="margin-top: 20px; margin-bottom: 20px;" alt="">
                                        @else
                                        <img src="{{asset('image/website/'.website()->icon)}}" width="80px" style="margin-top: 20px; margin-bottom: 20px;" alt="">
                                        @endif
                                  <br> {{website()->nama_website}}
        
                                        </td>
                                        <td width="50%" align="center" valign="top">
                                            Yang Menerima,<br><br><br><br><br><br><br><br><br>
                                            ..................................
                 
                                        </td>
                                    </tr>
                                </table>
                                       
                    </div>
    <script>
    @if(isset($print))
        window.print();
    @endif
    </script>
</body>

</html>
