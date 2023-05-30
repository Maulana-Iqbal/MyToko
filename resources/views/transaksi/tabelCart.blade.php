<?php
$totalModal=0;
$subHarga = 0;
$subBiaya = 0;
$totalBiaya = 0;
$subTotal=0;
$totalHarga=0;
$total=0;
?>
@if(session('cart'))
<?php $no = 1; ?>
@foreach(session('cart') as $index => $item)
<tr>
    <td width="40px">{{$no}}</td>
    <td>
        <input type="hidden" id="cartId" name="cartId" value="{{$item['id']}}">
        <input type="hidden" id="produkId" name="produkId" value="{{$item['produkId']}}">
        <input type="hidden" id="gudangId" name="gudangId" value="{{$item['gudangId']}}">
        <input type="hidden" name="harga_modal" id="cartHargaModal" value="{{number_format($item['harga_modal'],0,',','.')}}">
        <img src="/image/produk/small/{{$item['image']}}" alt="{{$item['name']}}" title="contact-img" class="rounded me-3" height="35" />
        <p class="m-0 d-inline-block align-middle text-wrap font-16">
            {!!$item['name']!!}
        </p>
    </td>
    <td width="150px" valign="center">
        {{$item['gudangName']}}
    </td>

    <td width="140px" valign="center">
        <input type="text" name="harga_final" value="{{number_format($item['harga_final'],0,',','.')}}" class="form-control required" readonly placeholder="">
    </td>
    <td width="140px" valign="center">
        <input type="text" name="biaya" id="cartBiaya" data-id="{{$item['id']}}" data-harga="{{$item['harga_final']}}" value="{{number_format($item['biaya'],0,',','.')}}" class="form-control rupiah" placeholder="">
    </td>
    <td width="140px" valign="center">
        <input type="text" name="" id="cartSub" readonly data-id="{{$item['id']}}" value="{{number_format($item['sub'],0,',','.')}}" class="form-control rupiah required" placeholder="">
    </td>
    <td width="50px" valign="center">
        <input type="text" min="1" name="jumlah" value="{{$item['jumlah']}}" class="form-control required" id="cartJumlah" data-id="{{$item['id']}}" data-cartprodukid="{{$item['produkId']}}" data-cartgudangid="{{$item['gudangId']}}" placeholder="Jumlah">
    </td>

    <td width="50px" valign="center" align="center">
        @if($item['grosir']=='1')
        <span class="text-success">Ya</span>
        @else
        <span class="text-warning">Tidak</span>
        @endif
    </td>
    <td width="150px">
        @php
        $subTotal=$item['sub']*$item['jumlah']
        @endphp
        <p id="cartTotal">Rp. {{number_format($subTotal,0,',','.')}}</p>
    </td>
    <td>
        <a href="javascript:void(0);" id="cartRemove" data-id="{{$item['id']}}" class="btn btn-outline-danger"> <i class="mdi mdi-delete"></i>
        </a>

    </td>
</tr>
<?php
// $modal=$item['price']*$item['quantity'];
// $totalModal+=$modal;

$subBiaya = $item['biaya'] * $item['jumlah'];
$subHarga = $item['harga_final'] * $item['jumlah'];
$totalBiaya+=$subBiaya;
$totalHarga+=$subHarga;
$total += $subTotal;
$no++;
?>
@endforeach
@endif
<script>
    $("#totalBiaya").val("{{$totalBiaya}}");
    $("#totalHarga").val("{{$totalHarga}}");
    $("#subTotal").val("{{$total}}");
    $(".totalBiaya").html("Rp. {{number_format($totalBiaya,0,',','.')}}")
    $(".totalHarga").html("Rp. {{number_format($totalHarga,0,',','.')}}")
    $(".subTotal").html("Rp. {{number_format($total,0,',','.')}}")
</script>
<?php
?>