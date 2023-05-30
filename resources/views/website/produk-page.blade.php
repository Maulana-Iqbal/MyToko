@extends('../index')

@section('content')

    <!-- <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet"> -->
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <style>
        /* @import url("https://fonts.googleapis.com/css2?family=Poppins:weight@100;200;300;400;500;600;700;800&display=swap"); */

        body {
            /* font-family: "Poppins", sans-serif; */
            /* font-weight: 300 */
        }

        .height {
            height: 100vh
        }

        .search {
            position: relative;
            box-shadow: 0 0 40px rgba(51, 51, 51, .1);
            /* margin-bottom: 30px; */
        }

        .search input {
            height: 50px;
            text-indent: 25px;
            border: 2px solid #d6d4d4
        }

        .search input:focus {
            box-shadow: none;
            border: 2px solid blue
        }

        .search .fa-search {
            position: absolute;
            top: 20px;
            left: 16px
        }

        .search button {
            position: absolute;
            top: 8px;
            right: 5px;
            height: 35px;
            width: 110px;
        }

        .img-thumbnail {
            opacity: 0.8;
        }

        .img-thumbnail:hover {
            opacity: 1;
        }


        .img-fluid {
            opacity: 0.8;
        }

        .img-fluid:hover {
            opacity: 1;
        }
    </style>







    <!--content-->
    <div id="productListDIV"
        style="margin:auto;  <?php if(count($produkSlider)>0){ ?> margin-top: 50px; <?php }else{ ?> margin-top: 100px; <?php } ?> margin-bottom:50px; width: 95%;">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-offset-3 col-md-6 col-offset-3">
                <div class="search"> <i class="fa fa-search"></i> <input type="text" id="search" name="search"
                        value="@if (isset($searchValue)) {{ $searchValue }} @endif" required class="form-control"
                        placeholder="Cari Produk"> <button type="button" id="cari"
                        class="btn btn-success" style="background-color: darkcyan">Cari</button>
                </div>
                {{-- @if (isset($total))
                    <div>
                        <h5 class="text-overflow mb-2">Ditemukan <span class="text-danger">{{ $total }}</span>
                            hasil
                        </h5>
                    </div>
                @endif --}}
                <div style="margin-bottom: 40px;"></div>
            </div>
        </div>

        <div class="row">
            <div class="d-none d-md-block col-md-3 d-lg-block col-lg-3 d-sm-none">


                <div class="border-end bg-white mb-4" id="sidebar-wrapper" style="margin-top:40px">
                    <div class="sidebar-heading border-bottom bg-light" style="padding: 20px;">Kategori Produk</div>
                    <div class="list-group list-group-flush" style="text-align: left;">
                        <a class="list-group-item list-group-item-action list-group-item-light p-3"
                            href="/produk-page">Semua Kategori</a>
                        @foreach ($kategori as $kat)
                            <a class="list-group-item list-group-item-action list-group-item-light p-3"
                                href="/produk-kategori/{{ $kat->slug }}">
                                <img height="25px" class="me-1" src="/image/kategori/tumb/{{ $kat->icon }}" alt="" />
                                {{ $kat->nama_kategori }}
                            </a>
                        @endforeach
                    </div>
                </div>

            </div>

            @if (isset($page))
                <div class="col-md-9 col-lg-9 col-sm-12">

                    <section id="pricing" class="pricing mt-4" style="padding: 0;">
                        <div class="container aos-init mt-4" data-aos="fade-up">

                            <div class="row gy-4 aos-init" id="data-wrapper" data-aos="fade-left"
                                style="margin-bottom: 50px;">
                            </div>
                            <div class="auto-load text-center">
                                <div class="spinner-border text-success" role="status"></div>
                            </div>

                        </div>

                    </section>

                </div>
            @else
                <div class="col-md-9 col-lg-9 col-sm-12">

                    <section id="pricing" class="pricing" style="padding: 0;">
                        <div class="container aos-init mt-4 mb-4" data-aos="fade-up">

                            <div class="row gy-4 aos-init" data-aos="fade-left" style="margin-bottom: 50px;">

                                @foreach ($produks as $pro)
                                    <div class="col-lg-3 col-md-3 col-sm-12 aos-init" data-aos="zoom-in"
                                        data-aos-delay="20">
                                        <div class="box" style="padding-top: 15px; padding-bottom: 15px;">
                                            <img src="/image/produk/large/{{ $pro->gambar_utama }}" class="img-fluid"
                                                style="padding: 0;" alt="" />
                                            <h3
                                                style="color: #2b2a2a; margin-bottom: 5px; font-size: 12px; font-weight: unset">
                                                {{ $pro->nama_produk }}</h3>
                                            <small
                                                style="color: #919191; margin-bottom: 5px; font-size: 12px; font-weight:unset">

                                                Kode : {{ $pro->kode_produk }}<br>

                                            </small>
                                            <?php
                                            if ($pro->jenis_jual == 1) {
                                                echo '<a
                                                                                                                                                                                                                                                                                                                                            href="/cart-add/' .
                                                    $pro->id .
                                                    '"
                                                                                                                                                                                                                                                                                                                                            class="btn btn-outline-dark m-2"><i class="fa fa-cart-plus text-danger"></i> Add To Cart</a>';
                                            } elseif ($pro->jenis_jual == 2) {
                                                echo '<a
                                                                                                                                                                                                                                                                                                                                            href="https://wa.me/6282283803383?text=Saya%20ingin%20memesan%20produk%20kode%20' .
                                                    $pro->kode_produk .
                                                    '%20nama%20produk%20' .
                                                    $pro->nama_produk .
                                                    '"
                                                                                                                                                                                                                                                                                                                                            class="btn btn-outline-success m-2"><i class="fa fa-whatsapp"></i>
                                                                                                                                                                                                                                                                                                                                            Pesan
                                                                                                                                                                                                                                                                                                                                            Sekarang</a>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                            {{ $produks->links('pagination::bootstrap-4') }}
                        </div>
                    </section>

                </div>
            @endif


        </div>
        <!--footer-->

        <!-- </div> -->
        <script>
            $(document).ready(function() {

                @if (isset($page))
                    var ENDPOINT = "{{ url('/') }}";
                    var page = 1;
                    infinteLoadMore(page);
                    $(window).scroll(function() {
                        if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
                            page++;
                            infinteLoadMore(page);
                        }
                    });

                    function infinteLoadMore(page) {
                        $.ajax({
                                url: ENDPOINT + "/{{ $page }}?page=" + page,
                                datatype: "html",
                                type: "get",
                                beforeSend: function() {
                                    $('.auto-load').show();
                                }
                            })
                            .done(function(response) {
                                if (response.length == 0) {
                                    $('.auto-load').html("We don't have more data to display :(");
                                    return;
                                }
                                $('.auto-load').hide();
                                $("#data-wrapper").append(response);
                            })
                            .fail(function(jqXHR, ajaxOptions, thrownError) {
                                console.log('Server error occured');
                            });
                    }
                @endif

                $(document).on("click", "#cari", function() {
                    param = $("#search").val();
                    param = param.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-')
                    window.location.href = "{{ url('produk-cari') }}" + '/' + param;
                });

            })
        </script>
    @endsection
