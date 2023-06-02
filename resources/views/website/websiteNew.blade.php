@extends("layouts.app")
@section('content')
<script src="//cdn.ckeditor.com/4.17.2/basic/ckeditor.js"></script>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">

            <h4 class="page-title">Tambah Toko Baru</h4>
        </div>
    </div>
</div>


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

                        <input type="hidden" required name="id" id="id">

                        <div class="mb-2">
                            <label for="nama" class="control-label">Nama Toko <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="nama" name="nama" required="">
                        </div>
                        <div class="mb-2">
                            <label for="username" class="control-label">Username Toko <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="username" name="username" required="">
                        </div>

                        <div class="mb-2">
                            <label for="nama_atasan" class="control-label">Nama CEO <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="nama_atasan" name="nama_atasan" value="" required="">
                        </div>

                        <div class="mb-2">
                            <label for="tagline" class="control-label">Tagline <span style="color: red;">*</span></label>

                            <input type="text" class="form-control" id="tagline" name="tagline">

                        </div>


                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-2">
                                    <label for="contact" class="control-label">Telpon / HP <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" id="contact" name="contact" required>
                                </div>
                                <div class="mb-2">
                                    <label for="whatsapp" class="control-label">WhatsApp</label>
                                    <input type="text" class="form-control" id="whatsapp" name="whatsapp">
                                </div>

                                <div class="mb-2">
                                    <label for="email" class="control-label">Email <span style="color: red;">*</span></label>
                                    <input type="email" class="form-control" id="email" required name="email">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-2">
                                    <label for="instagram" class="control-label">Instagram</label>
                                    <input type="text" class="form-control" id="instagram" name="instagram">
                                </div>
                                <div class="mb-2">
                                    <label for="tiktok" class="control-label">TikTok</label>
                                    <input type="text" class="form-control" id="tiktok" name="tiktok">
                                </div>
                                <div class="mb-2">
                                    <label for="facebook" class="control-label">Facebook</label>
                                    <input type="text" class="form-control" id="facebook" name="facebook">
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
                            <input class="form-control" type="text" name="kode_pos" placeholder="Enter your zip code" id="kode_pos" />
                        </div>

                        <div class="mb-2">
                            <label for="alamat" class="control-label">Alamat <span style="color: red;">*</span></label>
                            <textarea name="alamat" id="alamat" class="form-control"></textarea>
                        </div>

                        <div class="mb-2">
                            <label for="file" class="control-label">Icon Toko  <span style="color: red;">*</span></label>
                            <input type="file" id="file" name="file" required class="form-control">
                            <div class="mb-2">
                                <img id="preview-image-before-upload" alt="Preview Image" style="max-height: 100px; max-width:350px">
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="kop_surat" class="control-label">Gambar Kop Surat </label>
                            <input type="file" id="kop_surat" name="kop_surat" class="form-control">
                            <div class="mb-2">
                                <img id="preview-image-before-upload-kop" alt="Preview Image" style="max-height: 200px; max-width:100%">
                            </div>
                        </div>

                        <div class="mb-2 produk-group">
                            <label for="deskripsi" class="control-label">Deskripsi</label>

                            <textarea name="deskripsi" id="deskripsi" maxlength="300" class="form-control"></textarea>

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


<script>
    $(document).ready(function(e) {

        CKEDITOR.replace('deskripsi');
        CKEDITOR.replace('alamat');

        $('#preview-image-before-upload').attr('src', '');
        $('#file').change(function() {

            let reader = new FileReader();

            reader.onload = (e) => {

                $('#preview-image-before-upload').attr('src', e.target.result);
            }

            reader.readAsDataURL(this.files[0]);


        });

        $('#preview-image-before-upload-kop').attr('src', '');
        $('#kop_surat').change(function() {

            let reader = new FileReader();

            reader.onload = (e) => {

                $('#preview-image-before-upload-kop').attr('src', e.target.result);
            }

            reader.readAsDataURL(this.files[0]);


        });




        provinsi();

        function provinsi() {
            $.ajax({
                url: "/ongkir/provinsi/select",
                type: "post",
                dataType: 'json',
                success: function(params) {
                    $("#provinsi").select2({
                        placeholder: {
                            id: '', // the value of the option
                            text: 'Pilih Provinsi'
                        },
                        allowClear: true,
                        // dropdownParent: $('#newPelanggan .modal-body'),
                        //    _token: CSRF_TOKEN,
                        data: params // search term
                    });
                },
            });
        }

        function kabupaten(id) {
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
                        placeholder: {
                            id: '', // the value of the option
                            text: 'Pilih Kabupaten'
                        },
                        allowClear: true,
                        // dropdownParent: $('#newPelanggan .modal-body'),
                        //    _token: CSRF_TOKEN,
                        data: params // search term
                    });
                },
            });
        }

        function kecamatan(id) {
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
                        placeholder: {
                            id: '', // the value of the option
                            text: 'Pilih Kecamatan'
                        },
                        allowClear: true,
                        // dropdownParent: $('#newPelanggan .modal-body'),
                        //    _token: CSRF_TOKEN,
                        data: params // search term
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



    });
</script>
@endsection