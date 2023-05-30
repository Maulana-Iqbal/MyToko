@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Buat Barcode</h4>
        </div>
    </div>
</div>
<div class="row d-print-none">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="{{url('barcode')}}" method="get">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="mb-2">
                                <label for="produk">Cari Barang</label>
                                <a target="_blank" href="/produk"><small class="text-primary float-end">Buat Barang Baru</small></a>
                                <select multiple name="produk[]" id="produk" class="form-control">
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            <div class="mb-2">
                                <label for="">Jumlah</label>
                                <input type="number" name="jumlah" class="form-control" id="jumlah" required>
                            </div>
                        </div>
                        <div class="col-12 col-lg-2">
                            <div class="">
                                <input type="submit" value="Create" id="create" class="btn btn-primary m-2 p-2">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@if(isset($_GET['produk']) && isset($_GET['jumlah']))
<div class="row">
    <div class="col-12">
        <div class="card">
            
            <div class="card-header d-print-none ">
                <div class="text-start">
                    <a href="javascript:window.print()" class="btn btn-primary"><i class="mdi mdi-printer"></i> Print</a> 
                    <br><span>Saat Print Gunakan Layout <b>Landscape</b> </span>
                </div>
            </div>
            <div class="card-body">
                @foreach($produk as $produk)
                <h5>{{$produk->nama_produk}}</h5>
                <table class="table table-bordered" style="width: 96%;">
                    @php
                    $generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG();
                    @endphp
                    <?php $n = 0;
                    $j = $jumlah; ?>
                    @while($j>0)
                    @if($n==0)
                    <tr>
                        @endif
                        <td>
                            <img src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($produk->kode_produk, $generatorPNG::TYPE_CODE_128)) }}"><br>
                            <span class="text-end">Products: {{$produk->kode_produk}}</span>
                        </td>
                        @if($n==4)
                    </tr>
                    @endif

                    <?php $n++; ?>
                    <?php $j--; ?>

                    @if($n==4)
                    <?php $n = 0; ?>
                    @endif
                    @if($j==0 && $n<=3) </tr>
                        @endif
                        @endwhile
                </table>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif

<script>
    $(document).ready(function() {
        $("#produk").select2({
            placeholder: 'Cari Barang',
            allowClear: true,
            // dropdownParent: $('#modalStock .modal-body'),
            ajax: {
                url: "/produk/select",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        //    _token: CSRF_TOKEN,
                        search: params.term // search term
                    };
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }

        });

    })
</script>
@endsection