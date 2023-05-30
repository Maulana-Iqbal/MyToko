<!-- Modal -->
<div class="modal fade" id="newPelanggan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" @if (!isset($page)) style="width: 90%; max-width: 100%;" @endif>
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-info">
                <h5 class="modal-title" id="staticBackdropLabel">Informasi Pelanggan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form id="pelangganForm">



                    <div class="row">
                        @if (!isset($page))
                        <div class="col-lg-4">
                            <div class="mt-lg-0">
                                <h4 class="mt-2 p-1">Daftar Pelanggan</h4>
                                <P class="ms-2">Pilih pelanggan yang sudah ada</P>
                                <div class="app-search dropdown mb-2 p-2" style="min-height: 400px;">

                                    <div class="input-group">
                                        <input type="text" name="inputCari" class="form-control" placeholder="Cari Pelanggan" id="cariPelanggan">
                                        <span class="mdi mdi-magnify search-icon"></span>

                                    </div>

                                    <div id="listPelanggan" style="overflow: scroll">

                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-8">
                            <h4 class="mt-2 p-1">Customer</h4>
                            @else
                            <div class="col-lg-12">
                                @endif
                                <input type="hidden" id="pelangganId" name="pelangganId">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-2">
                                            <label for="perusahaan" class="form-label">Nama
                                                Perusahaan</label>
                                            <input class="form-control" type="text" name="perusahaan" placeholder="Enter your Company Name" id="perusahaan" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label for="billing-first-name" class="form-label">Nama
                                                Depan <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="nama_depan" placeholder="Enter your first name" id="nama_depan" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label for="billing-last-name" class="form-label">Nama
                                                Belakang <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="nama_belakang" placeholder="Enter your last name" id="nama_belakang" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label for="billing-email-address" class="form-label">Alamat
                                                Email
                                                <span class="text-danger">*</span></label>
                                            <input class="form-control" type="email" id="email" name="email" placeholder="Enter your email" id="billing-email-address" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label for="billing-phone" class="form-label">No.
                                                HP Aktif <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="telpon" placeholder="08xxxxxxxxxx" id="telpon" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4 class="mt-2">Alamat Pengiriman</h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label for="provinsi" class="form-label">Provinsi
                                                <span class="text-danger">*</span></label>
                                            <select name="provinsi" id="provinsi" class="form-control provinsi-tujuan">

                                                <option value="">Pilih Provinsi</option>

                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label for="kota" class="form-label">Kota /
                                                Kabupaten <span class="text-danger">*</span></label>
                                            <select name="kabupaten" id="kabupaten" class="form-control kota-tujuan">
                                                <option value="">Pilih Kota/Kabupaten
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label for="kecamatan" class="form-label">Kecamatan
                                                <span class="text-danger">*</span></label>
                                            <select name="kecamatan" id="kecamatan" class="form-control kecamatan-tujuan">

                                                <option value="">Pilih Kecamatan</option>

                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="mb-2">
                                            <label for="billing-zip-postal" class="form-label">Kode
                                                Pos <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="kode_pos" placeholder="Enter your zip code" id="kode_pos" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-2">
                                            <label for="billing-address" class="form-label">Alamat <span class="text-danger">*</span></label>
                                            <textarea name="alamat" id="alamat" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-white" data-bs-dismiss="modal">Batal</button>
                                <button type="button" class="btn btn-primary" id="saveBtn">Simpan</button>
                            </div>

                </form>
            </div>
        </div> <!-- end modal content-->
    </div> <!-- end modal dialog-->
</div> <!-- end modal-->


<script>
    $(document).ready(function() {
        $("#provinsi").select2({
            placeholder: 'Pilih Provinsi',
            allowClear: true,
            dropdownParent: $('#newPelanggan .modal-body'),
            ajax: {
                url: "/ongkir/provinsi/select",
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

        $("#kabupaten").select2({
            placeholder: 'Pilih Kota / Kabupaten',
            allowClear: true,
            dropdownParent: $('#newPelanggan .modal-body'),
            ajax: {
                url: "/ongkir/kabupaten/select",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        //    _token: CSRF_TOKEN,
                        search: params.term,
                        provinsi_id:$("#provinsi").val()
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

        $("#kecamatan").select2({
            placeholder: 'Pilih Kecamatan',
            allowClear: true,
            dropdownParent: $('#newPelanggan .modal-body'),
            ajax: {
                url: "/ongkir/kecamatan/select",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        //    _token: CSRF_TOKEN,
                        search: params.term,
                        kabupaten_id:$("#kabupaten").val()
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
        // provinsi();

        // function provinsi() {
        //     $.ajax({
        //         url: "/ongkir/provinsi/select",
        //         type: "post",
        //         dataType: 'json',
        //         success: function(params) {
        //             $("#provinsi").select2({
        //                 placeholder: {
        //                     id: '', // the value of the option
        //                     text: 'Pilih Provinsi'
        //                 },
        //                 allowClear: true,
        //                 dropdownParent: $('#newPelanggan .modal-body'),
        //                 //    _token: CSRF_TOKEN,
        //                 data: params // search term
        //             });
        //         },
        //     });
        // }

        // function kabupaten(id) {
        //     $.ajax({
        //         url: "/ongkir/kabupaten/select",
        //         type: "post",
        //         data: {
        //             provinsi_id: id
        //         },
        //         dataType: 'json',
        //         success: function(params) {
        //             $('#kabupaten').empty();
        //             $("#kabupaten").select2({
        //                 placeholder: {
        //                     id: '', // the value of the option
        //                     text: 'Pilih Kabupaten'
        //                 },
        //                 allowClear: true,
        //                 dropdownParent: $('#newPelanggan .modal-body'),
        //                 //    _token: CSRF_TOKEN,
        //                 data: params // search term
        //             });
        //         },
        //     });
        // }

        // function kecamatan(id) {
        //     $.ajax({
        //         url: "/ongkir/kecamatan/select",
        //         type: "post",
        //         data: {
        //             kabupaten_id: id
        //         },
        //         dataType: 'json',
        //         success: function(params) {
        //             $('#kecamatan').empty();
        //             $("#kecamatan").select2({
        //                 placeholder: {
        //                     id: '', // the value of the option
        //                     text: 'Pilih Kecamatan'
        //                 },
        //                 allowClear: true,
        //                 dropdownParent: $('#newPelanggan .modal-body'),
        //                 //    _token: CSRF_TOKEN,
        //                 data: params // search term
        //             });
        //         },
        //     });
        // }

        // // ajax select kota tujuan
        // $('select[name="provinsi"]').on('change', function() {
        //     let id = $(this).val();
        //     if (id) {
        //         kabupaten(id);
        //     }
        // });

        // // //ajax select kecamatan tujuan
        // $('select[name="kabupaten"]').on('change', function() {
        //     let id = $(this).val();
        //     if (id) {
        //         kecamatan(id);
        //     }
        // });
    })
</script>