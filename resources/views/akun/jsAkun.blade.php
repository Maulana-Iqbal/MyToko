<script type="text/javascript">
    $(document).ready(function() {
        //ajax setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        load_data();

        function load_data(from_date = '', to_date = '', website = '') {
          var table=$('#datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                retrieve: true,
                paging: true,
                destroy: true,
                "scrollX": false,
                ajax: {
                    url: "{{ url('/akunTable') }}",
                    type: "POST",
                    data: {
                        from_date: from_date,
                        to_date: to_date,
                        website: website,
                    }

                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'tipe',
                        name: 'tipe'
                    },
                    {
                        data: 'kode',
                        name: 'kode'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'kategori',
                        name: 'kategori'
                    },
                    {
                        data: 'saldo',
                        name: 'saldo'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
            table.draw();
        }


        // datatable
        var tableTrashed = $('#datatableTrash').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            retrieve: true,
            paging: true,
            destroy: true,
            "scrollX": false,
            ajax: {
                url: "{{ url('/akunTableTrash') }}",
                type: "POST",

            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'tipe',
                    name: 'tipe'
                },
                {
                    data: 'kode',
                    name: 'kode'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'kategori',
                    name: 'kategori'
                },
                {
                    data: 'saldo',
                    name: 'saldo'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });



        $('#master').on('click', function(e) {
            if ($(this).is(':checked', true)) {
                $(".select").prop('checked', true);
            } else {
                $(".select").prop('checked', false);
            }
        });

        $('.select2').select2({
            dropdownParent: $('#ajaxModel  .modal-body')
        });

        $('#filter').click(function() {
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                var website = $('#website').val();

                $('#datatable').DataTable().destroy();
                load_data(from_date, to_date, website);
                $("#print").val('Print');
                $("#export").val('Export');

            });

            $('#refresh').click(function() {
                $('#from_date').val('');
                $('#to_date').val('');
                $('#website').val('');
                $('#datatable').DataTable().destroy();
                load_data();
            });



        $('#createNewAkun').click(function() {
            $('#saveBtn').html("Simpan");
            $('#id_akun').val('');
            $('#akunForm').trigger("reset");
            $('#modelHeading').html("Tambah Akun");
            $('#ajaxModel').modal('show');
        });

        $('#saveBtn').click(function(e) {
            e.preventDefault();
            kode = $("#kode").val();
            name = $("#name").val();
            id = $('#id_akun').val();

            if (name == '' || kode == '') {
                Swal.fire({
                    title: 'Error!',
                    text: 'Data Belum Lengkap!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                })
            } else {


                $(this).html('Menyimpan..');

                var form = $('#akunForm')[0];
                var formData = new FormData(form);
                $("#canvasloading").show();
                $("#loading").show();
                $.ajax({
                    data: formData,
                    url: "{{ url('/akun/simpan') }}",
                    type: "POST",
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.success == true) {

                            $('#akunForm').trigger("reset");
                            $('#ajaxModel').modal('hide');
                            load_data()
                            $('#saveBtn').html('Simpan');
                            $("#canvasloading").hide();
                            $("#loading").hide();
                            Swal.fire({
                                title: 'Success!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            })
                        } else {
                            $('#saveBtn').html('Simpan');
                            $("#canvasloading").hide();
                            $("#loading").hide();
                            Swal.fire({
                                title: 'Error!',
                                text: data.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            })
                        }
                    },
                    error: function(xhr) {
                        var res = xhr.responseJSON;
                        if ($.isEmptyObject(res) == false) {
                            err = '';
                            $.each(res.errors, function(key, value) {
                                err = value + ', ';
                            });
                            $('#saveBtn').html('Simpan');
                            $("#canvasloading").hide();
                            $("#loading").hide();
                            Swal.fire({
                                title: 'Error!',
                                text: err,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            })
                        }
                    }
                });
            }
        });

        $('body').on('click', '.editAkun', function() {

            var id_akun = $(this).data('id_akun');
            $("#canvasloading").show();
            $("#loading").show();
            $.get("{{ url('/akun') }}" + '/' + id_akun + '/edit', function(data) {
                $("#canvasloading").hide();
                $("#loading").hide();
                $('#modelHeading').html("Ubah Akun ");
                $('#saveBtn').html('Perbaharui');
                $('#ajaxModel').modal('show');
                $('#id_akun').val(data.id);
                $('#kode').val(data.kode);
                $('#name').val(data.name);
                $('select[name="kategori_akun_id"]').val(data.kategori_akun_id);
                $('select[name="tipe"]').val(data.tipe);
                $('select[name="induk"]').val(data.induk);
            })

        });

        $('body').on('click', '.deleteAkun', function() {
            var id_akun = $(this).data("id_akun");
            status = $(this).data("status");

            myurl = '';
            if (status == 'trash') {
                myurl = "{{ url('/akun-trash') }}" + '/' + id_akun
                msg =
                    "Data Akun yang dihapus akan dipindahkan ke Tempat Sampah!";
            } else if (status == 'delete') {
                myurl = "{{ url('/akun-delete') }}" + '/' + id_akun
                msg =
                    "Data Akun yang dihapus tidak dapat dikembalikan!";
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
                                    Swal.fire({
                                        title: 'Deleted!',
                                        text: data.message,
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    })
                                } else {
                                    load_data();
                                    tableTrashed.draw();
                                    $("#canvasloading").hide();
                                    $("#loading").hide();
                                    Swal.fire({
                                        title: 'Gagal!',
                                        text: data.message,
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    })
                                }
                            },
                            error: function(data) {
                                console.log('Error:', data);
                                $("#canvasloading").hide();
                                $("#loading").hide();
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Terjadi Kesalahan',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                })
                            }
                        });

                    }
                    //  else {
                    //     $("#canvasloading").hide();
                    //     $("#loading").hide();
                    //     Swal.fire({
                    //         title: 'Batal!',
                    //         text: 'Hapus Data Dibatalkan',
                    //         icon: 'success',
                    //         confirmButtonText: 'OK'
                    //     })
                    // }
                });
        });


        $('body').on('click', '.restoreAkun', function() {

            var id_akun = $(this).data("id_akun");
            swal({
                    title: "Kembalikan Akun ini?",
                    text: "Data akan dikembalikan ke tabel Akun!",
                    type: "info",
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
                            url: "{{ url('/akun-restore') }}" + '/' + id_akun,
                            success: function(data) {
                                load_data();
                                tableTrashed.draw();
                                $("#canvasloading").hide();
                                $("#loading").hide();
                                swal("Success!", data.message,
                                    "success");
                            },
                            error: function(data) {
                                console.log('Error:', data);
                                $("#canvasloading").hide();
                                $("#loading").hide();
                            }
                        });

                    }

                });
        });

        $(document).on('click', '#bulk_delete', function() {
            var id = [];
            swal({
                    title: "Yakin hapus data yang dipilih?",
                    text: "Data yang dihapus akan dipindahkan ke Tempat Sampah!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ya, Hapus!",
                    cancelButtonText: "Batal!",
                    closeOnConfirm: true,
                    closeOnCancel: true
                },
                function(isConfirm) {
                    if (isConfirm) {
                        $('.select:checked').each(function() {
                            id.push($(this).val());
                        });
                        if (id.length > 0) {
                            $.ajax({
                                url: "{{ url('/akun/bulk-delete') }}",
                                method: "get",
                                data: {
                                    id: id
                                },
                                success: function(data) {
                                    if (data.success == true) {
                                        load_data();
                                        tableTrashed.draw();
                                        $("#canvasloading").hide();
                                        $("#loading").hide();
                                        Swal.fire({
                                            title: 'Deleted!',
                                            text: data.message,
                                            icon: 'success',
                                            confirmButtonText: 'OK'
                                        })
                                    } else {
                                        $("#canvasloading").hide();
                                        $("#loading").hide();
                                        Swal.fire({
                                            title: 'Gagal',
                                            text: data.message,
                                            icon: 'error',
                                            confirmButtonText: 'OK'
                                        })
                                    }
                                }
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Silahkan Pilih Data Yang Akan Dihapus...!!!',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            })
                        }
                    }
                });
        });


        $("body").on("change", "#kategori_akun_id", function() {
            var id = $(this).val();
            getInduk(id);
        });


        function getInduk(id = '') {
            $.ajax({
                url: "/akun/select-kategori",
                type: "post",
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(params) {
                    $('#induk').empty();
                    $("#induk").select2({
                        placeholder: {
                            id: '', // the value of the option
                            text: 'Pilih'
                        },
                        dropdownParent: $('#ajaxModel .modal-body'),
                        allowClear: true,
                        // dropdownParent: $('#newPelanggan .modal-body'),
                        //    _token: CSRF_TOKEN,
                        data: params // search term
                    });
                    // $("#induk").select2("trigger", "select", {
                    //     data: {
                    //         id: id
                    //     }
                    // });
                },
            });
        }



        // $('.chosen-select').chosen({width: "100%"});
    });
</script>
