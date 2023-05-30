@extends('../index')

@section('content')
    <style>
        .container {
            font-family: 'Verdana, Geneva, Tahoma, sans-serif';
        }
    </style>
    <section>
    <div class="container">
        
        <div class="row mt-4 mb-4">
            <div class="col-12 mt-4">
                
                        <div class="row">
                            <div class="col-lg-5">
                                <!-- Product image -->
                                <a href="{{ asset('image/produk/' . $produk->gambar_utama) }}"
                                    class="text-center d-block mb-4">
                                    <img src="{{ asset('image/produk/' . $produk->gambar_utama) }}" class="img-fluid"
                                        style="max-width: 280px;" alt="{{ $produk->nama_produk }}" />
                                </a>

                                <div class="d-lg-flex d-none justify-content-center">
                                    @php $i=1; @endphp
                                    @foreach ($galeri as $galeri)
                                        <a href="{{ url('/galeriImage/' . $galeri->gambar) }}"
                                            @if ($i > 1) class="ms-2" @endif>
                                            <img src="/galeriImage/{{ $galeri->gambar }}"
                                                class="img-fluid img-thumbnail p-2" style="max-width: 75px;"
                                                alt="{{ $produk->nama_produk }}" />
                                        </a>
                                        @php $i++ @endphp
                                    @endforeach
                                </div>
                            </div> <!-- end col -->
                            <div class="col-lg-7">

                                <!-- Product title -->
                                <h1 style="font-size:22px" class="mt-0">{{ $produk->nama_produk }}</h1>
                                <h3 style="font-size: 16px" class="d-flex"><img width="20px" class="me-1"
                                        src="{{ asset('image/kategori/tumb/' . $produk->kategori->icon) }}"
                                        alt="{{ $produk->kategori->nama_kategori }}">
                                    {{ $produk->kategori->nama_kategori }}
                                </h3>
                                <!-- <h6>Merek : {{ $produk->merek }}</h6> -->
                                <p style="font-size: 12px;" class="mb-1">Ditambahkan:
                                    <time>{{ tglIndo(date('Y-m-d',strtotime($produk->created_at))) }}</time>
                                </p>
                                <!-- <p class="font-16">
                                                                <span class="text-warning mdi mdi-star"></span>
                                                                <span class="text-warning mdi mdi-star"></span>
                                                                <span class="text-warning mdi mdi-star"></span>
                                                                <span class="text-warning mdi mdi-star"></span>
                                                                <span class="text-warning mdi mdi-star"></span>
                                                            </p> -->

                                <div class="mt-4">
                                    <label for="harga">Harga</label>
                                    <h5 id="harga">Rp.{{$stock->first()->harga_jual}}</h5>
                                </div>

                                <div class="mt-2">
                                    <div class="row">
                                        {{-- <div class="col-md-3">
                                            <select name="satuan" id="satuan" class="form-control select2">
                                                @foreach ($stock as $index => $satuan)
                                                    @if ($index == 0)
                                                        <option selected value="{{ $satuan->id }}">
                                                            {{ $satuan->satuan->name }}</span>
                                                        @else
                                                        <option value="{{ $satuan->id }}">
                                                            {{ $satuan->satuan->name }}</span>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div> --}}

                                        <div class="col-md-6">
                                            @if ($produk->jenis_jual == 1)
                                            <a href="javascript:void(0);" class="btn btn-outline-danger mb-1" id="btnAddToCart"><i class="fa fa-cart-plus"></i> Add To Cart</a>
                                            @elseif ($produk->jenis_jual == 2)
                                            <a href="https://wa.me/6282283803383?text=Saya%20ingin%20memesan%20produk%20kode%20{{ $produk->kode_produk . '%20nama%20produk%20' . $produk->nama_produk }}"
                                                class="btn btn-outline-success"><i class="fa fa-whatsapp"></i>
                                                Pesan
                                                Sekarang</a>
                                        @endif           
                                        </div>
                                    </div>

                                </div>
                                {{-- @if ($produk->jenis_juall == 1) --}}
                                {{-- <div class="mt-2">
                                            <h6 class="font-14">Daftar Harga:</h6>
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Stock</th>
                                                        <th>Harga</th>
                                                        <th>Jumlah Beli</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $i = 1; ?>
                                                    @foreach ($stock as $stock)
                                                        <form action="/cart-online/add" id="add-to-cart">
                                                            <tr>
                                                                <td>{{ $i }}</td>
                                                                <td>{{ $stock->jumlah }} {{ $stock->satuan->name }}
                                                                </td>
                                                                <td class="text-success">
                                                                    Rp.{{ number_format((float) $stock->harga_jual, 0, ',', '.') }}
                                                                    / {{ $stock->satuan->name }}</td>
                                                                <td><input required type="number" name="qty"></td>
                                                                <td>
                                                                    <button class="btn btn-sm"><i
                                                                            class="fa fa-cart-plus text-success"></i>
                                                                        Add</button>
                                                                </td>
                                                        </form>
                                                        </tr>

                                                        <?php $i++; ?>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                        </div> --}}
                                {{-- @endif --}}


                                



                            </div> <!-- end col -->
                        </div> <!-- end row-->

                        <div class="row justify-content-center">
                            <div class="col-10">
                                <div class="mt-4">
                                    <h6 class="font-14">Deskripsi Produk:</h6>
                                    {!! $produk->deskripsi !!}
                                </div>
                            </div>
                        </div>


            </div> <!-- end col-->
        </div>
        <!-- end row-->

    </div>
</section>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $("body").on("click","#btnAddToCart",function(){
                var stockId=$("#satuan").val();
                window.location.href="/cart/add"+"/"+stockId;
            })



            $("body").on("change", "#satuan", function() {
                var stockId = $(this).val();
                if (stockId == '') {
                    return;
                } else {
                    $.ajax({
                        type: "post",
                        url: "{{ url('/produk/get-harga-jual') }}",
                        data: {
                            stockId: stockId,
                        },
                        dataType: 'json',
                        success: function(data) {
                            console.log(data);
                            if (data.success == true) {
                                if (data.data.jumlah < 1) {
                                    swal("Pesan", "Stock Kosong", "error");
                                } else {
                                    $("#harga").html('Rp. ' + data.data.harga_jual)
                                    $("#jumlah").focus();
                                }
                            } else {
                                swal("Pesan", "Harga Jual tidak ditemukan", "error");
                            }

                            // swal("Pesan",data.message,"success");
                        }
                    });
                }
            })
        })
    </script>
@endsection
