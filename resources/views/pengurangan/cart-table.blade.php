<?php
$subTotal = 0;
$total = 0;
?>
@if(session('cart'))
<?php $no = 1; ?>
@foreach(session('cart') as $index => $item)
<tr>
    <td width="40px">{{$no}}</td>
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

    <td width="150px" valign="center">
        {{$item['gudangName']}}
    </td>
    <td width="100px" valign="center">
        <label for="">{{$item['price']}}</label>
    </td>
    <td width="100px" valign="center">
        <input type="text" min="1" value="{{$item['quantity']}}" autocomplete="off" class="form-control required" id="cartJumlah" data-id="{{$item['id']}}" data-cartprodukid="{{$item['produkId']}}" data-cartgudangid="{{$item['gudangId']}}" placeholder="Jumlah">
    </td>
   
    <td width="150px">
        @php
        $subTotal=$item['price']*$item['quantity']
        @endphp
        <p id="cartTotal">{{uang($subTotal)}}</p>
    </td>
    <td>
        <select name="kondisi[]" required class="form-control" id="kondisi" data-id="{{$item['id']}}">
            <option value="">Pilih Kondisi</option>
            <option @if($item['kondisi']=='rusak') selected @endif  value="rusak">Rusak</option>
            <option @if($item['kondisi']=='hilang') selected @endif value="hilang">Hilang</option>
            <option @if($item['kondisi']=='kedaluwarsa') selected @endif value="kedaluwarsa">Kedaluwarsa</option>
        </select>
    </td>
    <td width="80">
        <a href="javascript:void(0);" id="cartRemove" data-id="{{$item['id']}}" class="btn btn-outline-danger"> <i class="mdi mdi-delete"></i>
        </a>

    </td>
</tr>
<?php
$total += $subTotal;
$no++;
?>
@endforeach
<script>
    $(".total-cart").html("{{$total}}")
    $("#sub-total").val("{{$total}}")
    $("#grand_total_cell").html("{{$total}}")
    $("#total").val("{{$total}}")
    
</script>
@else
<tr>
    <td colspan="9" align="center">Cart Empty</td>
</tr>
@endif