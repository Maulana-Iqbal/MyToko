@extends('../index')

@section('content')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.3.2/dist/select2-bootstrap4.min.css"
        rel="stylesheet" />



    <form action="/cart/checkout2" method="POST">
        @csrf

<input type="hidden" name="destProvinsiId" id="destProvinsiId" value="{{$provinsiId}}">
<input type="hidden" name="destKotaId" id="destKotaId" value="{{$kotaId}}">
<input type="hidden" name="biayaKirim" id="biayaKirim" value="{{$biayaKirim}}">
<input type="hidden" name="kurir" id="kurir" value="{{$kurir}}">
<input type="hidden" name="service" id="service" value="{{$service}}">

        <div class="container mb-4" style="margin-top: 150px;">
            <div class="row mt-4">
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            Informasi Pelanggan
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="billing-first-name" class="form-label">Nama
                                                        Depan</label>
                                                    <input class="form-control" type="text" name="nama_depan" required
                                                        placeholder="Enter your first name" id="nama_depan" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="billing-last-name" class="form-label">Nama
                                                        Belakang</label>
                                                    <input class="form-control" type="text" name="nama_belakang"
                                                        placeholder="Enter your last name" id="nama_belakang" />
                                                </div>
                                            </div>
                                        </div> <!-- end row -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="perusahaan" class="form-label">Nama
                                                        Perusahaan</label>
                                                    <input class="form-control" type="text" name="perusahaan"
                                                        placeholder="Enter your Company Name" id="perusahaan" />
                                                </div>
                                            </div>
                                        </div> <!-- end row -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="billing-email-address" class="form-label">Alamat
                                                        Email
                                                        <span class="text-danger">*</span></label>
                                                    <input class="form-control" type="email" id="email" name="email"
                                                        placeholder="Enter your email" required
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
                                        </div> <!-- end row -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4 class="mt-2">Alamat Pelanggan</h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="provinsi" class="form-label">Provinsi</label>
                                                    <select name="provinsi" id="provinsi" required class="form-control">

                                                        <option value="">Pilih Provinsi</option>
                                                        @foreach ($allProvinsi as $prov)
                                                            <option value="{{ $prov->id }}">
                                                                {{ $prov->name }}</option>
                                                        @endforeach
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="kota" class="form-label">Kota /
                                                        Kabupaten</label>
                                                    <select name="kabupaten" id="kabupaten" required class="form-control">
                                                        <option value="">Pilih Kota/Kabupaten
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="kecamatan" class="form-label">Kecamatan</label>
                                                    <select name="kecamatan" id="kecamatan" required class="form-control">

                                                        <option value="">Pilih Kecamatan</option>

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="desa" class="form-label">Kelurahan /
                                                        Desa</label>
                                                    <select name="desa" id="desa" required class="form-control">

                                                        <option value="">Pilih Kelurahan/Desa
                                                        </option>

                                                    </select>
                                                </div>
                                            </div>
                                        </div> <!-- end row -->
                                        <div class="row">

                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="billing-zip-postal" class="form-label">Kode
                                                        Pos</label>
                                                    <input class="form-control" type="text" name="kode_pos"
                                                        placeholder="Enter your zip code" id="kode_pos" />
                                                </div>
                                            </div>
                                        </div> <!-- end row -->
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="mb-3">
                                                    <label for="billing-address" class="form-label">Alamat</label>
                                                    <textarea name="alamat" id="alamat" required class="form-control"></textarea>
                                                </div>
                                            </div>
                                        </div> <!-- end row -->

                                        {{-- <div class="row mt-4">

                                <div class="col-sm-12">
                                    <div class="d-grid">
                                        <button id="saveBtn" class="btn btn-primary btn-lg">
                                            <i class="mdi mdi-cash-multiple me-1"></i>
                                            Proses Sekarang
                                        </button>
                                    </div>
                                </div> <!-- end col -->
                            </div> <!-- end row --> --}}
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-3 pt-3 pt-md-0">
                    <div class="card">
                        <div class="card-header">Subtotal</div>
                        <div class="card-body">

                            <div class="h3 font-weight-semibold text-center py-3">
                                Rp.{{ number_format($subTotal, 0, ',', '.') }}</div>
                            <hr>

                            <div class="order-total table-responsive ">
                                <table class="table table-borderless text-right">
                                    <tbody>
                                        <tr>
                                            <td>Sub Total :</td>
                                            <td>Rp. {{ number_format($subTotal, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td>Shipping :</td>
                                            <td><span id="biayaKirim">Rp.
                                                    {{ number_format($biayaKirim, 0, ',', '.') }}</span></td>
                                        </tr>
                                        <tr>
                                            <td>Tax(10%) :</td>
                                            <td>Rp. {{ number_format($ppn, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="f-w-7 font-18">
                                                <h4>Total :</h4>
                                            </td>
                                            <td class="f-w-7 font-18">Rp. <span
                                                    id="grandTotal">{{ number_format($total, 0, ',', '.') }}</span>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>

                            </div>


                            <button type="submit" class="btn btn-primary btn-block">Checkout Now</button>

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </form>



    <script>
        $(document).ready(function() {
            //ajax setup
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#provinsi').change(function() {
                var provID = $(this).val();
                if (provID) {
                    $.ajax({
                        type: "GET",
                        url: "/kabupaten/" + provID,
                        dataType: 'JSON',
                        success: function(res) {
                            if (res) {
                                $("#kabupaten").empty();
                                $("#kecamatan").empty();
                                $("#desa").empty();
                                $("#kabupaten").append(
                                    '<option>---Pilih Kabupaten---</option>');
                                $("#kecamatan").append(
                                    '<option>---Pilih Kecamatan---</option>');
                                $("#desa").append('<option>---Pilih Desa---</option>');
                                $.each(res, function(i, val) {
                                    $("#kabupaten").append('<option value="' + val.id +
                                        '">' + val.name + '</option>');
                                });
                            } else {
                                $("#kabupaten").empty();
                                $("#kecamatan").empty();
                                $("#desa").empty();
                            }
                        }
                    });
                } else {
                    $("#kabupaten").empty();
                    $("#kecamatan").empty();
                    $("#desa").empty();
                }
            });

            $('#kabupaten').change(function() {
                var kabID = $(this).val();
                if (kabID) {
                    $.ajax({
                        type: "GET",
                        url: "/kecamatan/" + kabID,
                        dataType: 'JSON',
                        success: function(res) {
                            if (res) {
                                $("#kecamatan").empty();
                                $("#desa").empty();
                                $("#kecamatan").append(
                                    '<option>---Pilih Kecamatan---</option>');
                                $("#desa").append('<option>---Pilih Desa---</option>');
                                $.each(res, function(i, val) {
                                    $("#kecamatan").append('<option value="' + val.id +
                                        '">' + val.name + '</option>');
                                });
                            } else {
                                $("#kecamatan").empty();
                                $("#desa").empty();
                            }
                        }
                    });
                } else {
                    $("#kecamatan").empty();
                    $("#desa").empty();
                }
            });

            $('#kecamatan').change(function() {
                var kecID = $(this).val();
                if (kecID) {
                    $.ajax({
                        type: "GET",
                        url: "/desa/" + kecID,
                        dataType: 'JSON',
                        success: function(res) {
                            if (res) {
                                $("#desa").empty();
                                $("#desa").append('<option>---Pilih Desa---</option>');
                                $.each(res, function(i, val) {
                                    $("#desa").append('<option value="' + val.id +
                                        '">' +
                                        val.name + '</option>');
                                });
                            } else {
                                $("#desa").empty();
                            }
                        }
                    });
                } else {
                    $("#desa").empty();
                }
            });

        })
    </script>
@endsection
