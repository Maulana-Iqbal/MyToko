@extends('layouts.app')
@section('content')
<script src="//cdn.ckeditor.com/4.17.2/basic/ckeditor.js"></script>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">

            <h4 class="page-title">Pengaturan Toko</h4>
        </div>
    </div>
</div>
<ul class="nav nav-tabs nav-bordered mb-3">
    <li class="nav-item">
        <a href="#website" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
            <i class="mdi mdi-home-variant d-md-none d-block"></i>
            <span class="d-none d-md-block">Toko</span>
        </a>
    </li>
    <!-- <li class="nav-item">
        <a href="#transaksi" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
            <i class="mdi mdi-account-circle d-md-none d-block"></i>
            <span class="d-none d-md-block">Transaksi</span>
        </a>
    </li> -->
    <li class="nav-item">
        <a href="#prefix" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
            <i class="mdi mdi-account-circle d-md-none d-block"></i>
            <span class="d-none d-md-block">Prefix</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="#default" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
            <i class="mdi mdi-account-circle d-md-none d-block"></i>
            <span class="d-none d-md-block">Default Data</span>
        </a>
    </li>
</ul>

<div class="tab-content">
    <div class="tab-pane show active" id="website">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if (session()->has('status'))
                        <div class="alert alert-{{ session('status') }}" role="alert">
                            <strong>{{ strtoupper(session('status')) }} - </strong> {{ session('message') }}
                        </div>
                        @endif
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        {{-- <h4 class="header-title with-border">My Profil</h4> --}}
                        <form method="POST" action="/update-pengaturan-website" class="form-horizontal" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">

                                <input type="hidden" required name="id" id="id" value="{{ enc($website->id) }}">

                                <div class="mb-2">
                                    <label for="nama" class="control-label">Nama Toko <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" id="nama" name="nama" value="{{ $website->nama_website }}" required="">
                                </div>
                                <div class="mb-2">
                                    <label for="username" class="control-label">Username Toko <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" id="username" name="username" value="{{ $website->username }}" required="">
                                </div>

                                <div class="mb-2">
                                    <label for="nama_atasan" class="control-label">Nama Pemilik <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" id="nama_atasan" name="nama_atasan" value="{{ $website->nama_atasan }}" required="">
                                </div>

                                <div class="mb-2">
                                    <label for="tagline" class="control-label">Tagline <span style="color: red;">*</span></label>

                                    <input type="text" class="form-control" id="tagline" name="tagline" value="{{ $website->tagline }}">

                                </div>
                                <div class="row">
                                    <div class="col-12 col-lg-6">
                                        <div class="mb-2">
                                            <label for="contact" class="control-label">Telpon / HP <span style="color: red;">*</span></label>

                                            <input type="text" class="form-control" id="contact" name="contact" required value="{{ $website->contact }}">

                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        <div class="mb-2">
                                            <label for="whatsapp" class="control-label">WhatsApp</label>
                                            <input type="text" class="form-control" id="whatsapp" name="whatsapp" value="{{ $website->whatsapp }}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        <div class="mb-2">
                                            <label for="email" class="control-label">Email</label>
                                            <input type="text" class="form-control" id="email" name="email" value="{{ $website->email }}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        <div class="mb-2">
                                            <label for="instagram" class="control-label">Instagram</label>
                                            <input type="text" class="form-control" id="instagram" name="instagram" value="{{ $website->instagram }}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        <div class="mb-2">
                                            <label for="tiktok" class="control-label">TikTok</label>
                                            <input type="text" class="form-control" id="tiktok" name="tiktok" value="{{ $website->tiktok }}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        <div class="mb-2">
                                            <label for="facebook" class="control-label">Facebook</label>
                                            <input type="text" class="form-control" id="facebook" name="facebook" value="{{ $website->facebook }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label class="">Provinsi <span style="color: red;">*</span></label>
                                    <select class="custom-select form-control provinsi-tujuan" required name="province_destination" id="provinsi">
                                        <option value="">Pilih Provinsi
                                        </option>

                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label class="">Kota
                                        / Kabupaten <span style="color: red;">*</span></label>
                                    <select class="custom-select form-control kota-tujuan" required name="city_destination" id="kabupaten">
                                        <option value="">Pilih Kota /
                                            Kabupaten</option>

                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label class="">Kecamatan <span style="color: red;">*</span></label>
                                    <select class="custom-select form-control kecamatan-tujuan" required name="district_destination" id="kecamatan">
                                        <option value="">Pilih Kecamatan
                                        </option>

                                    </select>
                                </div>

                                <div class="mb-2">
                                    <label class="">Kode
                                        Pos <span style="color: red;">*</span></label>
                                    <input class="form-control" type="text" value="{{ $website->kode_pos }}" name="kode_pos" placeholder="Enter your zip code" id="kode_pos" />
                                </div>

                                <div class="mb-2">
                                    <label for="alamat" class="control-label">Alamat <span style="color: red;">*</span></label>
                                    <textarea name="alamat" id="alamat" class="form-control"></textarea>
                                </div>

                                <div class="mb-2">
                                    <label for="file" class="control-label">Icon Toko <span style="color: red;">*</span></label>
                                    <input type="file" id="file" name="file" class="form-control">
                                    <div class="mb-2">
                                        <img id="preview-image-before-upload" alt="Preview Image" style="max-height: 100px; max-width:350px">
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <label for="kop_surat" class="control-label">Gambar Kop Toko </label>
                                    <input type="file" id="kop_surat" name="kop_surat" class="form-control">
                                    <div class="mb-2">
                                        <img id="preview-image-before-upload-kop" alt="Preview Image" style="max-height: 200px; max-width:100%">
                                    </div>
                                </div>

                                <div class="mb-2 produk-group">
                                    <label for="deskripsi" class="control-label">Deskripsi Toko</label>

                                    <textarea name="deskripsi" id="deskripsi" maxlength="300" class="form-control"></textarea>

                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary" id="saveBtn">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="tab-pane" id="transaksi">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">


                        <form method="POST" action="/update-pengaturan-transaksi" class="form-horizontal" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">

                                <input type="hidden" required name="id" id="id" value="{{ enc($website->id) }}">

                                <div class="row">
                                    <div class="col-md-6">
                                        <h3>PPN & PPH</h3>
                                        <label for="ppn">PPn %</label>
                                        <input type="number" max="100" min="0" name="ppn" id="ppn" value="{{ $website->trx_ppn }}" class="form-control">
                                        <label for="pph">PPh %</label>
                                        <input type="number" max="100" min="0" name="pph" id="pph" value="{{ $website->trx_pph }}" class="form-control">
                                    </div>

                                    <div class="col-md-6" style="display: none">
                                        <h3>MarkUp Harga</h3>
                                        <label for="markup"> Transaksi Offline %</label>
                                        <input type="number" max="100" min="0" name="markup" id="markup" value="0" class="form-control">
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <h3>Verifikasi Atasan</h3>
                                        <label for="verifikasi">Apakah transaksi offline perlu verifikasi
                                            atasan?</label>

                                        <div class="mt-2">
                                            <div class="form-check">
                                                <input type="radio" id="verifikasi1" name="verifikasi" value="1" class="form-check-input">
                                                <label class="form-check-label" for="verifikasi1">Ya</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" id="verifikasi2" name="verifikasi" value="0" class="form-check-input">
                                                <label class="form-check-label" for="verifikasi2">Tidak</label>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <h3>Durasi Invoice Transaksi (Detik)</h3>
                                        <label for="duration_online">Transaksi Online</label>
                                        <input type="number" min="0" name="duration_online" id="duration_online" value="{{ $website->trx_duration_online }}" class="form-control">
                                        <label for="duration_offline">Transaksi Offline</label>
                                        <input type="number" min="0" name="duration_offline" id="duration_offline" value="{{ $website->trx_duration_offline }}" class="form-control">

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <h3>Prefix</h3>
                                        <div class="mb-2">
                                            <label for="trx_prefix">Prefix Transaksi</label>
                                            <input type="text" name="trx_prefix" id="trx_prefix" value="{{ $website->trx_prefix }}" class="form-control">
                                        </div>
                                        <div class="mb-2">
                                            <label for="quo_prefix">Prefix Penawaran</label>
                                            <input type="text" name="quo_prefix" id="quo_prefix" value="{{ $website->quo_prefix }}" class="form-control">
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary" id="saveBtn">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <div class="tab-pane" id="prefix">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Prefix</h3>
                    </div>
                    <div class="card-body">


                        <form method="POST" action="{{url('prefix/store')}}" class="form-horizontal">
                            @csrf
                            <div class="modal-body">

                                <input type="hidden" required name="website_id" value="{{ enc($website->id) }}">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-2">
                                            <label for="produk">Produk</label>
                                            <input type="text" name="produk" id="produk" value="{{ $website->prefix->produk }}" class="form-control">
                                        </div>
                                        <div class="mb-2">
                                            <label for="gudang">Gudang</label>
                                            <input type="text" name="gudang" id="gudang" value="{{ $website->prefix->gudang }}" class="form-control">
                                        </div>
                                        <div class="mb-2">
                                            <label for="supplier">Supplier</label>
                                            <input type="text" name="supplier" id="supplier" value="{{ $website->prefix->pemasok }}" class="form-control">
                                        </div>
                                        <div class="mb-2">
                                            <label for="sales">Sales</label>
                                            <input type="text" name="sales" id="sales" value="{{ $website->prefix->sales }}" class="form-control">
                                        </div>
                                        <div class="mb-2">
                                            <label for="pembelian">Pembelian</label>
                                            <input type="text" name="pembelian" id="pembelian" value="{{ $website->prefix->pembelian }}" class="form-control">
                                        </div>
                                        <div class="mb-2">
                                            <label for="penjualan">Penjualan</label>
                                            <input type="text" name="penjualan" id="penjualan" value="{{ $website->prefix->penjualan }}" class="form-control">
                                        </div>
                                        <div class="mb-2">
                                            <label for="penjualan">Pengurangan Stock</label>
                                            <input type="text" name="pengurangan" id="pengurangan" value="{{ $website->prefix->pengurangan }}" class="form-control">
                                        </div>
                                        <div class="mb-2">
                                            <label for="stocktransfer">Transfer Stock</label>
                                            <input type="text" name="stocktransfer" id="stocktransfer" value="{{ $website->prefix->stocktransfer }}" class="form-control">
                                        </div>
                                        <div class="mb-2">
                                            <label for="preorder">Purchase Order</label>
                                            <input type="text" name="preorder" id="preorder" value="{{ $website->prefix->preorder }}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary" id="saveBtn">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-pane" id="default">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Default Data</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{url('default-data/store')}}" class="form-horizontal">
                            @csrf
                            <div class="modal-body">

                                <input type="hidden" required name="website_id" value="{{ enc($website->id) }}">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-2">
                                            <label for="gudang-data" class="control-label">Gudang</label>
                                            <a target="_blank" href="/gudang"><small class="text-primary float-end">Buat Gudang Baru</small></a>
                                            <select id="gudang-data" name="gudang" class="form-control select2">
                                                <option value="">Pilih Gudang</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-2">
                                            <label for="supplier-data" class="control-label">Supplier <span style="color: red;">*</span></label>
                                            <a target="_blank" href="/supplier"><small class="text-primary float-end">Buat Supplier Baru</small></a>
                                            <select id="supplier-data" name="supplier" required class="form-control select2">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-2">
                                            <label for="sales-data" class="control-label">Sales <span style="color: red;">*</span></label>
                                            <a target="_blank" href="/sales"><small class="text-primary float-end">Buat Sales Baru</small></a>
                                            <select id="sales-data" name="sales" required class="form-control select2">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-2">
                                            <label for="customer-data" class="control-label">Customer <span style="color: red;">*</span></label>
                                            <a target="_blank" href="/pelanggan"><small class="text-primary float-end">Buat Customer Baru</small></a>
                                            <select id="customer-data" name="customer" required class="form-control select2">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(e) {

        var id = "{{ $website->provinsi }}";

        provinsi(id);

        function provinsi(id = '') {
            $.ajax({
                url: "/ongkir/provinsi/select",
                type: "post",
                dataType: 'json',
                success: function(params) {
                    $("#provinsi").select2({
                        allowClear: true,
                        // dropdownParent: $('#newPelanggan .modal-body'),
                        //    _token: CSRF_TOKEN,
                        data: params // search term
                    });
                    $("#provinsi").select2("trigger", "select", {
                        data: {
                            id: "{{ $website->provinsi }}"
                        }
                    });
                },
            });
        }
        id = "{{ $website->provinsi }}";
        kabupaten(id);

        function kabupaten(id = '') {
            $.ajax({
                url: "/ongkir/kabupaten/select",
                type: "post",
                data: {
                    provinsi_id: id
                },
                dataType: 'json',
                success: function(params) {
                    $('#kabupaten').empty();
                    $("#kabupaten").select2({
                        allowClear: true,
                        // dropdownParent: $('#newPelanggan .modal-body'),
                        //    _token: CSRF_TOKEN,
                        data: params // search term
                    });

                    $("#kabupaten").select2("trigger", "select", {
                        data: {
                            id: "{{ $website->kota }}"
                        }
                    });
                },
            });
        }
        id = "{{ $website->kabupaten }}";
        kecamatan(id);

        function kecamatan(id = '') {
            $.ajax({
                url: "/ongkir/kecamatan/select",
                type: "post",
                data: {
                    kabupaten_id: id
                },
                dataType: 'json',
                success: function(params) {
                    $('#kecamatan').empty();
                    $("#kecamatan").select2({
                        allowClear: true,
                        // dropdownParent: $('#newPelanggan .modal-body'),
                        //    _token: CSRF_TOKEN,
                        data: params // search term
                    });

                    $("#kecamatan").select2("trigger", "select", {
                        data: {
                            id: "{{ $website->kecamatan }}"
                        }
                    });
                },
            });
        }

        // ajax select kota tujuan
        $('#provinsi').on('change', function() {
            let id = $(this).val();
            if (id) {
                kabupaten(id);
            }
        });

        // //ajax select kecamatan tujuan
        $('#kabupaten').on('change', function() {
            let id = $(this).val();
            if (id) {
                kecamatan(id);
            }
        });



        CKEDITOR.replace('deskripsi');
        CKEDITOR.replace('alamat');

        CKEDITOR.instances['deskripsi'].setData('{!! $website->description !!}');
        CKEDITOR.instances['alamat'].setData('{!! $website->address !!}');


        $('#preview-image-before-upload').attr('src', '/image/website/{{ $website->icon }}');
        $('#file').change(function() {

            let reader = new FileReader();

            reader.onload = (e) => {

                $('#preview-image-before-upload').attr('src', e.target.result);
            }

            reader.readAsDataURL(this.files[0]);


        });
        <?php
        if ($website->kop_surat <> '') {
        ?>
            $('#preview-image-before-upload-kop').attr('src', '/image/website/kop/{{ $website->kop_surat }}');
        <?php } ?>
        $('#kop_surat').change(function() {

            let reader = new FileReader();

            reader.onload = (e) => {

                $('#preview-image-before-upload-kop').attr('src', e.target.result);
            }

            reader.readAsDataURL(this.files[0]);


        });

        // <?php if ($website->trx_verifikasi) { ?>
        //     var $radios = $('input:radio[name=verifikasi]');
        //     if ($radios.is(':checked') === false) {
        //         $radios.filter('[value={{ $website->trx_verifikasi }}]').prop('checked', true);
        //     }
        // <?php } else { ?>
        //     var $radios = $('input:radio[name=verifikasi]');
        //     if ($radios.is(':checked') === false) {
        //         $radios.filter('[value={{ $website->trx_verifikasi }}]').prop('checked', true);
        //     }
        // <?php } ?>

        $("#produk-data").select2({
            placeholder: 'Cari Produk',
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

        $("#gudang-data").select2({
            placeholder: 'Pilih Gudang',
            allowClear: true,
            // dropdownParent: $('#modalStock .modal-body'),
            ajax: {
                url: "/gudang/select",
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

        $("#supplier-data").select2({
            placeholder: 'Pilih Supplier',
            allowClear: true,
            // dropdownParent: $('#modalStock .modal-body'),
            ajax: {
                url: "/supplier/select",
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

        $("#customer-data").select2({
            placeholder: 'Pilih Customer',
            allowClear: true,
            // dropdownParent: $('#modalStock .modal-body'),
            ajax: {
                url: "/pelanggan/select",
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

        $("#sales-data").select2({
            placeholder: 'Pilih Sales',
            allowClear: true,
            // dropdownParent: $('#modalStock .modal-body'),
            ajax: {
                url: "/sales/select",
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

        $("#sales-data").select2("trigger", "select", {
            data: {
                id: '{{$website->default->sales_id??""}}',
                text: '{{$website->default->sales->nama??""}}'
            }
        });
        $("#customer-data").select2("trigger", "select", {
            data: {
                id: '{{$website->default->pelanggan_id??""}}',
                text: '{{$website->default->customer->nama_depan??""}} {{$website->default->customer->nama_belakang??""}}'
            }
        });
        $("#supplier-data").select2("trigger", "select", {
            data: {
                id: '{{$website->default->pemasok_id??""}}',
                text: '{{$website->default->supplier->nama??""}}'
            }
        });
        $("#gudang-data").select2("trigger", "select", {
            data: {
                id: '{{$website->default->gudang_id??""}}',
                text: '{{$website->default->gudang->nama??""}}'
            }
        });

    });
</script>
@endsection