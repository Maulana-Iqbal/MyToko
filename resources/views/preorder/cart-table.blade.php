<?php
$subTotal = 0;
$total = 0;
?>
@if(session('cart'))
<?php $no = 1; ?>
@foreach(session('cart') as $index => $item)
<tr>
    <td width="40px" align="center">{{$no}}</td>
    <td width="80px">
        <input type="hidden" name="kode" class="form-control" value="{{$item['kode']}}">
        <label for="">{{$item['kode']}}</label>
    </td>
    <td width="200px">
        <input type="hidden" id="cartId" name="cartId" value="{{$item['id']}}">
        <input type="hidden" id="produkId" name="produkId" value="{{$item['produkId']}}">
        <input type="hidden" id="gudangId" name="gudangId" value="{{$item['gudangId']}}">
        <img src="{{$item['image']}}" alt="{{$item['name']}}" title="contact-img" class="rounded me-3" height="35" />
        <p class="m-0 d-inline-block align-middle text-wrap font-16">
            {!!$item['name']!!}
        </p>
    </td>

    <td width="150px" >
        {{$item['gudangName']}}
    </td>
    <td width="100px"  align="right">
        <label for="">{{uang($item['price'])}}</label>
    </td>
    <td width="100px" >
        <input type="text" min="1" value="{{$item['quantity']}}" class="form-control required" id="cartJumlah" data-id="{{$item['id']}}" data-cartprodukid="{{$item['produkId']}}" data-cartgudangid="{{$item['gudangId']}}" placeholder="Jumlah">
    </td>
    
    <td width="150px" align="right">
        @php
        $subTotal=$item['price']*$item['quantity']
        @endphp
        <p id="cartTotal">{{uang($subTotal)}}</p>
    </td>
    <td width="80" align="center">
        <a href="javascript:void(0);" id="cartRemove" data-id="{{$item['id']}}"> <i class="mdi mdi-delete"></i>
        </a>

    </td>
</tr>
<?php
$total += $subTotal;
$no++;
?>
@endforeach
<script>
    $(".total-cart").html("{{uang($total)}}")
    $("#sub-total").val("{{$total}}")
    $("#grand_total_cell").html("{{$total}}")
    $("#total").val("{{$total}}")
    
</script>
@else
<tr>
    <td colspan="9" align="center">Cart Empty</td>
</tr>
@endif