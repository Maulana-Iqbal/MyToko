<script src="//cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>
<script type="text/javascript">
    $(document).ready(function() {


        function kode_produk() {
            $.ajax({
                url: "{{ url('/produk/kode') }}",
                type: "get",
                dataType: 'json',
                success: function(data) {
                    $("#kode_produk").val(data.data);
                },
            });
        }


        load_data();

        function load_data(produk = null, kategori = null, satuan = null, website = null) {
            var table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                retrieve: true,
                paging: true,
                destroy: true,

                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                scrollX: false,
                ajax: {
                    url: "{{ url('/produkTable') }}",
                    type: "POST",
                    data: {
                        produk: produk,
                        kategori: kategori,
                        satuan: satuan,
                        website: website,
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'kode_produk',
                        name: 'kode_produk'
                    },
                    {
                        data: 'nama_produk',
                        name: 'nama_produk'
                    },
                    {
                        data: 'nama_kategori',
                        name: 'nama_kategori'
                    },
                    {
                        data: 'jumlah',
                        name: 'jumlah'
                    },
                    {
                        data: 'harga',
                        name: 'harga'
                    },
                    {
                        data: 'harga_jual',
                        name: 'harga_jual'
                    },
                    {
                        data: 'harga_grosir',
                        name: 'harga_grosir'
                    },
                    {
                        data: 'profit',
                        name: 'profit'
                    },
                    {
                        data: 'laba',
                        name: 'laba'
                    },
                    {
                        data: 'qr',
                        name: 'qr'
                    },

                ]
            });
            table.draw();
        }

        // 






        $('#filter').click(function() {
            var produk = $("#produkFilter").val();
            var kategori = $("#kategoriFilter").val();
            var satuan = $("#satuanFilter").val();
            var website = $("#website").val();
            $('#datatable').DataTable().destroy();
            load_data(produk, kategori, satuan, website);
            // $("#print").val('Print');
            // $("#export").val('Export');

        });



        $('#refresh').click(function() {
            $('#form-filter-produk').trigger("reset");
            $("#produkFilter").val('').trigger('change');
            $("#kategoriFilter").val('').trigger('change');
            $("#satuanFilter").val('').trigger('change');
            $("#website").val('').trigger('change');
            $('#datatable').DataTable().destroy();
            load_data();
        });

        // $('.select2').select2({
        //     dropdownParent: $('#ajaxModel .modal-body')
        // });



        $("body").on("keyup release focusout change", "#nama_produk", function() {
            nama_produk = $("#nama_produk").val();
            nama_produk = nama_produk.replace(/ /g, '-');
            $("#slug").val(nama_produk);
        })

        $('#createNewProduk').click(function() {
            $('#saveBtn').html("Simpan");
            $('#id_produk').val('');
            $(".edt1").css('display', 'block');
            $(".edt2").css('display', 'none');
            // $('.inputHargaJual').css('display','none');
            $("#btnModalStock").css('display', 'none');
            $('#produkForm').trigger("reset");
            CKEDITOR.instances['deskripsi'].setData('');
            CKEDITOR.instances['keterangan'].setData('');
            $('#preview-image-before-upload').attr('src', '');
            $('#modelHeading').html("Tambah Barang ");
            $("#jumlah, #harga, #harga_jual, #harga_grosir").removeAttr('readonly');
            $("#kategori").select2("val", '0');
            $("#satuan").select2("val", '0');
            kode_produk();
            $('#ajaxModel').modal('show');
        });

        $('#saveBtn').click(function(e) {
            e.preventDefault();
            $(this).html('Menyimpan..');
            for (instance in CKEDITOR.instances) {
                $('#' + instance).val(CKEDITOR.instances[instance].getData());
            }
            var form = $('#produkForm')[0];
            var formData = new FormData(form);
            var id_produk = $("#id_produk").val();
            var url = "{{ url('/produk/simpan') }}";
            if (id_produk != '') {
                url = "{{ url('/produk/update') }}";
            }
            $("#canvasloading").show();
            $("#loading").show();
            $.ajax({
                data: formData,
                url: url,
                type: "POST",
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data.success === true) {

                        $('#produkForm').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        load_data();
                        $('#saveBtn').html('Simpan');
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        $.NotificationApp.send("Berhasil", data.message, "top-right", "",
                            "info")
                    } else {
                        $('#saveBtn').html('Simpan');
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        swal("Pesan", data.message, "error");
                    }
                },
                error: function(xhr) {
                    var res = xhr.responseJSON;
                    if ($.isEmptyObject(res) == false) {
                        err = '';
                        $.each(res.errors, function(key, value) {
                            // err += value + ', ';
                            err = value;
                        });
                        $('#saveBtn').html('Simpan');
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        swal("Pesan", err, "error");
                    }
                }
            });
        });




        $('body').on('click', '.editProduk', function() {
            $('#preview-image-before-upload').attr('src', '');
            var id_produk = $(this).data('id_produk');
            $.get("{{ url('/produk') }}" + '/' + id_produk + '/edit', function(data) {
                $('#modelHeading').html("Ubah Barang ");
                $('#saveBtn').html('Perbaharui');
                $('#ajaxModel').modal('show');
                $("#jumlah, #harga, #harga_jual, #harga_grosir").prop('readonly', true);
                $('#id_produk').val(id_produk);
                $('#nama_produk').val(data.data.nama_produk);
                $('#slug').val(data.data.slug);
                $('#kode_produk').val(data.data.kode_produk);
                $('#merek').val(data.data.merek);
                $('#harga').val(data.data.stock.harga);
                $('#harga_jual').val(data.data.stock.harga_jual);
                $('#harga_grosir').val(data.data.stock.harga_grosir);
                $('#jumlah').val(data.data.stock.jumlah);
                $('#berat').val(data.data.berat);
                $('#min_stock').val(data.data.min_stock);
                // $('#keterangan').val(data.data.keterangan);
                CKEDITOR.instances['keterangan'].setData(data.data.keterangan);
                $('#preview-image-before-upload').attr('src', '/image/produk/' + data.data.gambar_utama);
                CKEDITOR.instances['deskripsi'].setData(data.data.deskripsi);
                $("#kategori").select2("trigger", "select", {
                    data: {
                        id: data.data.kategori_id,
                        text: data.data.kategori.nama_kategori
                    }
                });

                $("#satuan").select2("trigger", "select", {
                    data: {
                        id: data.data.satuan_id,
                        text: data.data.satuan.name
                    }
                });
                $(".edt1").css('display', 'none');
                $(".edt2").css('display', 'block');
            })

        });

        $('body').on('click', '.deleteProduk', function() {
            var id_produk = $(this).data("id_produk");
            status = $(this).data("status");

            myurl = '';
            if (status == 'trash') {
                myurl = "{{ url('/produk-trash') }}" + '/' + id_produk
                msg =
                    "Data Barang yang dihapus akan dipindahkan ke Tempat Sampah termasuk Data Stock dari Barang ini! ";
            } else if (status == 'delete') {
                myurl = "{{ url('/produk-delete') }}" + '/' + id_produk
                msg =
                    "Data Barang yang dihapus tidak dapat dikembalikan!";
            }
            swal({
                    title: "Yakin hapus data ini?",
                    text: msg,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ya, Hapus Data!",
                    cancelButtonText: "Batal!",
                    closeOnConfirm: true,
                    closeOnCancel: true
                },
                function(isConfirm) {
                    if (isConfirm) {
                        $("#canvasloading").show();
                        $("#loading").show();
                        $.ajax({
                            type: "get",
                            url: myurl,
                            success: function(data) {
                                if (data.success == true) {
                                    load_data();
                                    tableTrashed.draw();
                                    $("#canvasloading").hide();
                                    $("#loading").hide();
                                    $.NotificationApp.send("Delete", data.message,
                                        "top-right", "",
                                        "info")
                                } else {
                                    $("#canvasloading").hide();
                                    $("#loading").hide();
                                    swal("Gagal!", data.message,
                                        "error");
                                }
                            },
                            error: function(data) {
                                load_data();
                                tableTrashed.draw();
                                console.log('Error:', data);
                                $("#canvasloading").hide();
                                $("#loading").hide();
                            }
                        });

                    } else {
                        load_data();
                        tableTrashed.draw();
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        swal("Cancelled", "Hapus data dibatalkan...! :)", "error");
                    }
                });
        });


        $('body').on('click', '.restoreProduk', function() {

            var id_produk = $(this).data("id_produk");
            swal({
                    title: "Kembalikan Barang ini?",
                    text: "Data akan dikembalikan ke tabel Barang!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ya, Kembalikan!",
                    cancelButtonText: "Batal!",
                    closeOnConfirm: true,
                    closeOnCancel: true
                },
                function(isConfirm) {
                    if (isConfirm) {
                        $("#canvasloading").show();
                        $("#loading").show();
                        $.ajax({
                            type: "get",
                            url: "{{ url('/produk-restore') }}" + '/' + id_produk,
                            success: function(data) {
                                load_data();
                                tableTrashed.draw();
                                $("#canvasloading").hide();
                                $("#loading").hide();
                                $.NotificationApp.send("Berhasil", "Telah Dikembalikan",
                                    "top-right", "",
                                    "info")
                            },
                            error: function(data) {
                                console.log('Error:', data);
                                $("#canvasloading").hide();
                                $("#loading").hide();
                            }
                        });

                    } else {
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        swal("Cancelled", "Barang gagal Dikembalikan...! :)", "error");
                    }
                });
        });



        $('body').on('click', '.copyProduk', function() {

            var id_produk = $(this).data("id_produk");
            swal({
                    title: "Yakin Duplicate Barang ini?",
                    text: "Barang yang sama akan ditambahkan!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#536de6",
                    confirmButtonText: "Ya, Copy Data!",
                    cancelButtonText: "Batal!",
                    closeOnConfirm: false,
                    closeOnCancel: true
                },
                function(isConfirm) {
                    if (isConfirm) {
                        $("#canvasloading").show();
                        $("#loading").show();
                        $.ajax({
                            type: "get",
                            url: "{{ url('/copy-produk') }}" + '/' + id_produk,
                            success: function(data) {
                                load_data();
                                $("#canvasloading").hide();
                                $("#loading").hide();
                                swal("Berhasil!", "Barang telah di Duplicate...!",
                                    "success");
                            },
                            error: function(data) {
                                console.log('Error:', data);
                                $("#canvasloading").hide();
                                $("#loading").hide();
                                swal("Pesan", data.message, "error");
                            }
                        });

                    }
                    //  else {
                    //     swal("Cancelled", "Duplicate data dibatalkan...! :)", "error");
                    // }
                });
        });




        // datatable
        var tableTrashed = $('#datatableTrash').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            retrieve: true,
            paging: true,
            destroy: true,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            "scrollX": false,
            ajax: {
                url: "{{ url('/produkTrashTable') }}",
                type: "POST",

            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                }, {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'kode_produk',
                    name: 'kode_produk'
                },
                {
                    data: 'nama_produk',
                    name: 'nama_produk'
                },
                {
                    data: 'nama_kategori',
                    name: 'nama_kategori'
                },
                {
                    data: 'jumlah',
                    name: 'jumlah'
                },
                {
                    data: 'harga',
                    name: 'harga'
                },
                {
                    data: 'harga_jual',
                    name: 'harga_jual'
                },
                {
                    data: 'harga_grosir',
                    name: 'harga_grosir'
                },
                {
                    data: 'profit',
                    name: 'profit'
                },
                {
                    data: 'laba',
                    name: 'laba'
                },
                {
                    data: 'qr',
                    name: 'qr'
                },

            ]
        });




        $('body').on('click', '.deleteGaleri', function() {

            var id_gambar = $(this).data("id_gambar");
            var id_produk = $(this).data("id_produk");
            swal({
                    title: "Yakin hapus data ini?",
                    text: "Data yang sudah dihapus tidak dapat dikembalikan!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ya, Hapus Data!",
                    cancelButtonText: "Batal!",
                    closeOnConfirm: false,
                    closeOnCancel: false
                },
                function(isConfirm) {
                    if (isConfirm) {
                        $("#canvasloading").show();
                        $("#loading").show();
                        $.ajax({
                            type: "DELETE",
                            url: "{{ url('/hapus-galeri') }}" + '/' + id_gambar,
                            success: function(data) {
                                $('.list-gambar-produk').load('/get-gambar-produk' +
                                    '/' +
                                    id_produk);
                                $("#canvasloading").hide();
                                $("#loading").hide();
                                swal("Deleted!", "Data Berhasil Dihapus...!",
                                    "success");
                            },
                            error: function(data) {
                                console.log('Error:', data);
                                $("#canvasloading").hide();
                                $("#loading").hide();
                                swal("Pesan", data.message, "error");
                            }
                        });

                    } else {
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        swal("Cancelled", "Hapus data dibatalkan...! :)", "error");
                    }
                });
        });


        $('body').on('click', '.gambarProduk', function() {
            id = $(this).data('id_produk');
            $("#gambarForm").trigger('reset');
            $("#dataId").val(id);
            $('.list-gambar-produk').load('/get-gambar-produk' + '/' + id);
            $('#gambarModal').modal('show');
        });

        $('#saveGambar').click(function(e) {
            e.preventDefault();
            var form = $('#gambarForm')[0];
            var formData = new FormData(form);
            $("#canvasloading").show();
            $("#loading").show();
            $.ajax({
                data: formData,
                url: "{{ url('/simpan-gambar') }}",
                type: "POST",
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data.success == true) {

                        $('#gambarForm').trigger("reset");
                        $('#gambarModal').modal('hide');
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        swal("Pesan", data.message, "success");
                    } else {
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        swal("Pesan", data.message, "error");
                    }
                },
                error: function(xhr) {
                    var res = xhr.responseJSON;
                    if ($.isEmptyObject(res) == false) {
                        err = '';
                        $.each(res.errors, function(key, value) {
                            err += value + ', ';
                        });
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        swal("Pesan", err, "error");
                    }
                }
            });
        });

        $("#kategori").select2({
            placeholder: 'Pilih Kategori',
            allowClear: true,
            dropdownParent: $('#ajaxModel .modal-body'),
            ajax: {
                url: "/kategori/select",
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

        $("#satuan").select2({
            placeholder: 'Pilih Satuan',
            allowClear: true,
            dropdownParent: $('#ajaxModel .modal-body'),
            ajax: {
                url: "/satuan/select",
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




        $("#produkFilter").select2({
            // placeholder: 'Cari Produk',
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

        $("#satuanFilter").select2({
            // placeholder: 'Pilih Satuan',
            allowClear: true,
            // dropdownParent: $('#modalStock .modal-body'),
            ajax: {
                url: "/satuan/select",
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

        $("#kategoriFilter").select2({
            // placeholder: 'Pilih Kategori',
            allowClear: true,
            // dropdownParent: $('#modalStock .modal-body'),
            ajax: {
                url: "/kategori/select",
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

        $("#website").select2({});

        CKEDITOR.replace('deskripsi');
        CKEDITOR.replace('keterangan');



        $('#file').change(function(e) {

            let reader = new FileReader();

            reader.onload = (e) => {

                $('#preview-image-before-upload').attr('src', e.target.result);
            }

            reader.readAsDataURL(this.files[0]);

        });
    });
</script>