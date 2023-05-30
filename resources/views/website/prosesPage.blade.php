@extends("index")

@section("content")

<section style="margin-top: 100px;">
    <header class="section-header" style="padding-bottom: 10px;">
        <h2>{{$pesan}}</h2>
        {{-- <p>Cek Produk Terbaru Kami</p> --}}
    </header>
    <p class="text-center">Kode Transaksi:</p>
    <h3 class='text-center'>{{$result}}</h3>

</section>


@endsection