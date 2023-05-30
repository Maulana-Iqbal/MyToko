@extends('../index')

@section('content')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.3.2/dist/select2-bootstrap4.min.css"
        rel="stylesheet" />


    <style>
        .cart-item-thumb {
            display: block;
            width: 10rem
        }

        .cart-item-thumb>img {
            display: block;
            width: 100%
        }

        .product-card-title>a {
            color: #222;
        }

        .font-weight-semibold {
            font-weight: 600 !important;
        }

        .product-card-title {
            display: block;
            margin-bottom: .75rem;
            padding-bottom: .875rem;
            border-bottom: 1px dashed #e2e2e2;
            font-size: 1rem;
            font-weight: normal;
        }

        .text-muted {
            color: #888 !important;
        }

        .bg-secondary {
            background-color: #f7f7f7 !important;
        }

        .accordion .accordion-heading {
            margin-bottom: 0;
            font-size: 1rem;
            font-weight: bold;
        }

        .font-weight-semibold {
            font-weight: 600 !important;
        }







        #msform {
            text-align: center;
            position: relative;
            margin-top: 20px
        }

        #msform fieldset .form-card {
            background: white;
            border: 0 none;
            border-radius: 0px;
            box-shadow: 0 2px 2px 2px rgba(0, 0, 0, 0.2);
            padding: 20px 40px 30px 40px;
            box-sizing: border-box;
            width: 94%;
            margin: 0 3% 20px 3%;
            position: relative
        }

        #msform fieldset {
            background: white;
            border: 0 none;
            border-radius: 0.5rem;
            box-sizing: border-box;
            width: 100%;
            margin: 0;
            padding-bottom: 20px;
            position: relative
        }

        #msform fieldset:not(:first-of-type) {
            display: none
        }

        #msform fieldset .form-card {
            text-align: left;
            color: #9E9E9E
        }

        /* #msform input,
                                            #msform textarea {
                                                padding: 0px 8px 4px 8px;
                                                border: none;
                                                border-bottom: 1px solid #ccc;
                                                border-radius: 0px;
                                                margin-bottom: 25px;
                                                margin-top: 2px;
                                                width: 100%;
                                                box-sizing: border-box;
                                                font-family: montserrat;
                                                color: #2C3E50;
                                                font-size: 16px;
                                                letter-spacing: 1px
                                            }

                                            #msform input:focus,
                                            #msform textarea:focus {
                                                -moz-box-shadow: none !important;
                                                -webkit-box-shadow: none !important;
                                                box-shadow: none !important;
                                                border: none;
                                                font-weight: bold;
                                                border-bottom: 2px solid skyblue;
                                                outline-width: 0
                                            } */

        #msform .action-button {
            width: 100px;
            background: skyblue;
            font-weight: bold;
            color: white;
            border: 0 none;
            border-radius: 0px;
            cursor: pointer;
            padding: 10px 5px;
            margin: 10px 5px
        }

        #msform .action-button:hover,
        #msform .action-button:focus {
            box-shadow: 0 0 0 2px white, 0 0 0 3px skyblue
        }

        #msform .action-button-previous {
            width: 100px;
            background: #616161;
            font-weight: bold;
            color: white;
            border: 0 none;
            border-radius: 0px;
            cursor: pointer;
            padding: 10px 5px;
            margin: 10px 5px
        }

        #msform .action-button-previous:hover,
        #msform .action-button-previous:focus {
            box-shadow: 0 0 0 2px white, 0 0 0 3px #616161
        }

        /* select.list-dt {
                                                border: none;
                                                outline: 0;
                                                border-bottom: 1px solid #ccc;
                                                padding: 2px 5px 3px 5px;
                                                margin: 2px
                                            }

                                            select.list-dt:focus {
                                                border-bottom: 2px solid skyblue
                                            } */

        .cardStep {
            z-index: 0;
            border: none;
            border-radius: 0.5rem;
            position: relative
        }

        .fs-title {
            font-size: 25px;
            color: #2C3E50;
            margin-bottom: 10px;
            font-weight: bold;
            text-align: left
        }

        #progressbar {
            margin-bottom: 30px;
            overflow: hidden;
            color: lightgrey
        }

        #progressbar .active {
            color: #000000
        }

        #progressbar li {
            list-style-type: none;
            font-size: 12px;
            width: 25%;
            float: left;
            position: relative
        }

        #progressbar #account:before {
            font-family: FontAwesome;
            content: "\f023"
        }

        #progressbar #personal:before {
            font-family: FontAwesome;
            content: "\f007"
        }

        #progressbar #payment:before {
            font-family: FontAwesome;
            content: "\f09d"
        }

        #progressbar #confirm:before {
            font-family: FontAwesome;
            content: "\f00c"
        }

        #progressbar li:before {
            width: 50px;
            height: 50px;
            line-height: 45px;
            display: block;
            font-size: 18px;
            color: #ffffff;
            background: lightgray;
            border-radius: 50%;
            margin: 0 auto 10px auto;
            padding: 2px
        }

        #progressbar li:after {
            content: '';
            width: 100%;
            height: 2px;
            background: lightgray;
            position: absolute;
            left: 0;
            top: 25px;
            z-index: -1
        }

        #progressbar li.active:before,
        #progressbar li.active:after {
            background: skyblue
        }
    </style>

    <section style="margin-top: 100px;">
        <div class="container pb-5 mt-n2 mt-md-n3">
            @if (Cart::isEmpty())
                <div class="m-2 bg-greed rounded">
                    <p class="text-success">Keranjang Belanja Masih Kosong <a class="font-size-sm" href="/produk-page"><svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-chevron-left"
                                style="width: 1rem; height: 1rem;">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>Lanjutkan Belanja</a></p>
                </div>
            @else
                <div class="row">
                    <div class="col-xl-12 col-md-12">
                        @if ($message = Session::get('success'))
                            <div class="m-2 bg-greed rounded">
                                <p class="text-success">{{ $message }}</p>
                            </div>
                        @endif
                        @if ($message = Session::get('alert'))
                            <div class="m-2 bg-red rounded">
                                <p class="text-danger">{{ $message }}</p>
                            </div>
                        @endif

                        <div id="cartElement">
                            <h2
                                class="h6 d-flex flex-wrap justify-content-between align-items-center px-4 py-3 bg-secondary">
                                <span>Daftar Belanja</span><a class="font-size-sm" href="/produk-page"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-chevron-left"
                                        style="width: 1rem; height: 1rem;">
                                        <polyline points="15 18 9 12 15 6"></polyline>
                                    </svg>Lanjutkan Belanja</a>
                            </h2>
                            <!-- Item-->
                            <?php
                            $totalHarga = 0;
                            $totalBerat = 0;
                            $subTotalHarga = 0;
                            ?>
                            @foreach ($cartItems as $index => $item)
                                <div class="d-sm-flex justify-content-between my-4 pb-4 border-bottom">
                                    <div class="media d-block d-sm-flex text-center text-sm-left">
                                        <a class="cart-item-thumb mx-auto mr-sm-4" href="javascript:void(0);"><img
                                                src="/image/produk/large/{{ $item->image }}" alt="Product"></a>
                                        <div class="media-body pt-3">
                                            <h3 class="product-card-title font-weight-semibold border-0 pb-0">
                                                <a href="javascript:void(0);">{{ $item->name }}</a>
                                            </h3>
                                            {{-- <div class="font-size-sm"><span class="text-muted mr-2">Size:</span>8.5</div>
                                <div class="font-size-sm"><span class="text-muted mr-2">Color:</span>Black</div> --}}
                                            <div class="font-size-lg text-info">Harga Rp.
                                                {{ number_format($item->price, 0, ',', '.') }} / {{ $item->satuan }}
                                            </div>
                                            <div class="font-size-sm pt-2"><span
                                                    class="text-muted mr-2">{{ number_format($item->price, 0, ',', '.') }}
                                                    X
                                                    {{ $item->quantity }}</span></div>
                                            <div class="font-size-lg text-primary pt-2">Sub
                                                Total Rp.
                                                {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pt-2 pt-sm-0 pl-sm-3 mx-auto mx-sm-0 text-center text-sm-left"
                                        style="max-width: 10rem;">
                                        <div class="form-group mb-2">
                                            <label for="quantity1">Quantity</label>
                                            <form action="{{ route('cart.update') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $item->id }}">
                                                <input type="number" class="form-control form-control-sm" name="quantity"
                                                    value="{{ $item->quantity }}" min="1">
                                                <button type="submit"
                                                    class="btn btn-outline-secondary btn-sm btn-block mb-2 mt-2">Perbarui</button>
                                            </form>
                                        </div>

                                        <div class="form-group mb-2">
                                            <form action="{{ route('cart.remove') }}" method="POST">
                                                @csrf
                                                <input type="hidden" value="{{ $item->id }}" name="id">
                                                <button class="btn btn-outline-danger btn-sm btn-block mb-2"><i
                                                        class="fa fa-trash"></i>
                                                    Remove</button>
                                            </form>
                                        </div>


                                    </div>
                                </div>
                                <?php
                                $subTotalBerat = $item->berat * $item->quantity;
                                $totalBerat += $subTotalBerat;

                                $subHarga = $item->price * $item->quantity;
                                $totalHarga += $subHarga;
                                $subTotalHarga += $totalHarga;
                                ?>
                            @endforeach

                            <!-- Sidebar-->

                            <a href="javascript:void(0);" id="selanjutnya"
                                class="btn btn-success btn-block mt-4 p-2">Selanjutnya</a>
                        </div>



                        <div id="informElement" style="display: none">
                            <form action="/transaksi/checkout" method="POST">
                                @csrf
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <h2
                                            class="h6 d-flex flex-wrap justify-content-between align-items-center px-4 py-3 bg-secondary">
                                            <span>Informasi Pembeli</span>
                                        </h2>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="billing-first-name" class="form-label">Nama
                                                        Depan <span class="text-danger">*</span></label></label>
                                                    <input class="form-control" type="text" name="nama_depan" required
                                                        placeholder="Enter your first name" id="nama_depan" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="billing-last-name" class="form-label">Nama
                                                        Belakang <span class="text-danger">*</span></label></label>
                                                    <input class="form-control" type="text" name="nama_belakang"
                                                        placeholder="Enter your last name" id="nama_belakang" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="billing-email-address" class="form-label">Email
                                                        <span class="text-danger">*</span></label>
                                                    <input class="form-control" type="email" id="email"
                                                        name="email" placeholder="Enter your email" required
                                                        id="billing-email-address" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="billing-phone" class="form-label">Nomor Handphone <span
                                                            class="text-danger">*</span></label>
                                                    <input class="form-control" type="text" name="telpon" required
                                                        placeholder="08xxxxxxxxxx" id="telpon" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h2
                                                    class="h6 d-flex flex-wrap justify-content-between align-items-center px-4 py-3 bg-secondary">
                                                    <span>Informasi Pengiriman</span>
                                                </h2>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label class="">Provinsi
                                                                Tujuan <span class="text-danger">*</span></label></label>
                                                            <select class="custom-select form-control provinsi-tujuan"
                                                                required name="province_destination">
                                                                <option value="">Pilih Provinsi
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="">Kota
                                                                / Kabupaten Tujuan <span
                                                                    class="text-danger">*</span></label></label>
                                                            <select class="custom-select form-control kota-tujuan" required
                                                                name="city_destination">
                                                                <option value="">Pilih Kota /
                                                                    Kabupaten</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label class="">Kecamatan
                                                                Tujuan <span class="text-danger">*</span></label></label>
                                                            <select class="custom-select form-control kecamatan-tujuan"
                                                                required name="district_destination">
                                                                <option value="">Pilih Kecamatan
                                                                </option>
                                                            </select>
                                                        </div>


                                                        <div class="form-group">
                                                            <label class="">Kode
                                                                Pos <span class="text-danger">*</span></label></label>
                                                            <input class="form-control" type="text" name="kode_pos"
                                                                placeholder="Enter your zip code" id="kode_pos" />
                                                        </div>


                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label class="">Alamat
                                                                Lengkap <span class="text-danger">*</span></label></label>
                                                            <textarea name="alamat" id="alamat" required class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-6">

                                                        <div class="form-group">
                                                            <label class="">Jasa
                                                                Pengiriman <span
                                                                    class="text-danger">*</span></label></label>
                                                            <select class="form-control kurir" required name="courier">
                                                                <option value="">Pilih Kurir
                                                                </option>
                                                                <option value="jne">JNE</option>
                                                                <option value="pos">POS</option>
                                                                <option value="tiki">TIKI
                                                                </option>
                                                            </select>
                                                        </div>

                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label class="">Paket <span
                                                                    class="text-danger">*</span></label></label>
                                                            <select class="form-control paket" required name="paket">
                                                                <option value="">Pilih Paket
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>




                                        <h3 class="h6 font-weight-semibold"><span
                                                class="badge badge-success mr-2">Note</span>Catatan
                                            Pembelian</h3>
                                        <textarea class="form-control mb-4" name="catatan" id="order-comments" rows="5"></textarea>


                                        <input type="hidden" id="total-berat" name="total-berat"
                                            value="{{ $totalBerat }}">
                                        <input type="hidden" id="service" name="service" value="">


                                        <h2 class="h6 px-4 py-3 bg-secondary text-center">Subtotal</h2>
                                        <div class="h3 font-weight-semibold text-center py-3">
                                            Rp.{{ number_format($totalHarga, 0, ',', '.') }}</div>
                                        <hr>

                                        <div class="order-total table-responsive ">
                                            <table class="table table-borderless text-right">
                                                <tbody>

                                                    <tr>
                                                        <td>Pengiriman :</td>
                                                        <td><span id="biayaKirim">Rp. 0</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>PPN({{ website(1)->trx_ppn }}%) :</td>
                                                        <td>{{ number_format($ppn, 0, ',', '.') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="f-w-7 font-18">
                                                            <h4>Total :</h4>
                                                        </td>
                                                        <td class="f-w-7 font-18">Rp. <span
                                                                id="grandTotal">{{ number_format(Cart::getTotal(), 0, ',', '.') }}</span>
                                                        </td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                            <input type="hidden" value="{{ Cart::getTotal() }}" id="cartTotal">
                                        </div>




                                        <button type="submit" class="btn btn-primary btn-block mt-4 p-2">Buat Tagihan
                                            Pembayaran</button>
                                    </div>








                                </div>
                            </form>
                        </div>
                    </div>





                </div>
            @endif




        </div>
    </section>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            //ajax setup
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            //active select2
            $(".provinsi-asal , .kota-asal, .provinsi-tujuan, .kota-tujuan, .kecamatan-tujuan").select2();

            $("#loading").show();
            $.ajax({
                type: 'get',
                url: '{{ url('ongkir/get-all-provinsi') }}',
                success: function(data) {
                    $("select[name=province_destination]").html(data);

                    $("#loading").hide();
                }
            })


            //ajax select kota tujuan
            $('select[name="province_destination"]').on('change', function() {
                let provindeId = $(this).val();
                if (provindeId) {

                    $("#loading").show();
                    $.ajax({
                        type: 'get',
                        url: '{{ url('ongkir/get-all-kota') }}' + '/' + provindeId,
                        success: function(data) {
                            $("select[name=city_destination]").html(data);

                            $("#loading").hide();
                        }
                    })
                } else {
                    $('select[name="city_destination"]').append(
                        '<option value="">-- pilih kota tujuan --</option>');
                }
            });

            //ajax select kecamatan tujuan
            $('select[name="city_destination"]').on('change', function() {
                let cityId = $(this).val();
                if (cityId) {

                    $("#loading").show();
                    $.ajax({
                        type: 'get',
                        url: '{{ url('ongkir/get-all-kecamatan') }}' + '/' + cityId,
                        success: function(data) {
                            $("select[name=district_destination]").html(data);

                            $("#loading").hide();
                        }
                    })
                } else {
                    $('select[name="district_destination"]').append(
                        '<option value="">-- pilih Kecamatan Tujuan --</option>');
                }
            });
            //ajax check ongkir
            let isProcessing = false;
            $('select[name="courier"]').on('change', function() {
                let token = $("meta[name='csrf-token']").attr("content");
                let provinces = $('select[name="province_destination"]');
                let city_destination = $('select[name=city_destination]').val();
                let courier = $('select[name=courier]').val();
                let weight = $('#total-berat').val();
                if (provinces == '' || city_destination == '') {
                    alert('Silahkan Pilih Provinsi dan Kota / Kabupaten Tujuan');
                    return;
                }

                $("#loading").show();



                if (isProcessing) {
                    return;
                }

                isProcessing = true;
                jQuery.ajax({
                    url: "/ongkir/costs",
                    data: {
                        _token: token,
                        tujuan: city_destination,
                        kurir: courier,
                        berat: weight,
                    },
                    dataType: "html",
                    type: "POST",
                    success: function(data) {
                        isProcessing = false;
                        if (data) {
                            $("select[name=paket]").html(data);

                            $("#loading").hide();
                        }
                    }
                });

            });

            $('select[name="paket"]').on('change', function() {

                let val = $(this).val();
                var service = $('option:selected', this).attr('data-paket');
                let totalHarga = "{{ Cart::getTotal() }}";
                let total = 0;

                if (val != "0") {
                    $("#loading").show();
                    $.ajax({
                        type: 'POST',
                        url: '{{ url('cart/add-ongkir') }}',
                        dataType: 'json',
                        data: {
                            ongkir: val
                        },
                        success: function(data) {
                            if (data.success) {
                                $("#biayaKirim").html(val);
                                $("#service").val(service);
                                total = parseInt(totalHarga) + parseInt(val);
                                $("#grandTotal").html(total);
                            } else {
                                alert('Gagal menambahkan Ongkos Kirim');
                            }

                        }

                    })

                    $("#loading").hide();

                }
            });


            // $("#select > option").on("click", function() {
            //     alert(1)
            // })

            $(document).on("click", "#selanjutnya", function() {
                $("#informElement").show();
                $(this).hide();
            })

        });
    </script>
@endsection
