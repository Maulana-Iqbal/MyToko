<script type="text/javascript">
    $(document).ready(function() {
        //ajax setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // datatable
        var table = $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            retrieve: true,
            paging: true,
            destroy: true,
            "scrollX": false,
            ajax: {
                url: "{{ url('/rekeningTable') }}",
                type: "POST",

            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'select',
                    name: 'select',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'nama_bank',
                    name: 'nama_bank'
                },
                {
                    data: 'nama_rek',
                    name: 'nama_rek'
                },
                {
                    data: 'no_rek',
                    name: 'no_rek'
                },
                {
                    data: 'jenis_rek',
                    name: 'jenis_rek'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });


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
                url: "{{ url('/rekeningTableTrash') }}",
                type: "POST",

            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'nama_bank',
                    name: 'nama_bank'
                },
                {
                    data: 'nama_rek',
                    name: 'nama_rek'
                },
                {
                    data: 'no_rek',
                    name: 'no_rek'
                },
                {
                    data: 'jenis_rek',
                    name: 'jenis_rek'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
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


        $("#akun").select2({
            placeholderOption: 'Pilih Akun',
            allowClear: true,
            dropdownParent: $('#ajaxModel .modal-body'),
            ajax: {
                url: "/akun/select",
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


        function getAkun(id = '') {
            $.ajax({
                url: "/akun/select",
                type: "post",
                dataType: 'json',
                success: function(params) {
                    $('#akun').empty();
                    $("#akun").select2({
            dropdownParent: $('#ajaxModel .modal-body'),
                        allowClear: true,
                        // dropdownParent: $('#newPelanggan .modal-body'),
                        //    _token: CSRF_TOKEN,
                        data: params // search term
                    });
                    $("#akun").select2("trigger", "select", {
                        data: {
                            id: id
                        }
                    });
                },
            });
        }


        $('#createNewRekening').click(function() {
            $('#saveBtn').html("Simpan");
            $('#id_rekening').val('');
            $('#rekeningForm').trigger("reset");
            $('#modelHeading').html("Tambah Rekening ");
            $('#ajaxModel').modal('show');
        });

        $('#saveBtn').click(function(e) {
            e.preventDefault();
            id = $('#id_rekening').val();
            $(this).html('Menyimpan..');

            var form = $('#rekeningForm')[0];
            var formData = new FormData(form);
            $("#canvasloading").show();
            $("#loading").show();
            $.ajax({
                data: formData,
                url: "{{ url('/rekening/simpan') }}",
                type: "POST",
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data.success == true) {

                        $('#rekeningForm').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        table.draw();
                        $('#saveBtn').html('Simpan');
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        $.NotificationApp.send("Berhasil",
                            data.message, "top-right", "",
                            "info")
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
        });

        $('body').on('click', '.editRekening', function() {

            var id_rekening = $(this).data('id_rekening');
            $("#canvasloading").show();
            $("#loading").show();
            $.get("{{ url('/rekening') }}" + '/' + id_rekening + '/edit', function(data) {
                $("#canvasloading").hide();
                $("#loading").hide();
                $('#modelHeading').html("Ubah Rekening ");
                $('#saveBtn').html('Perbaharui');
                $('#ajaxModel').modal('show');
                $('#id_rekening').val(data.id);
                $('#nama_bank').val(data.nama_bank);
                $('#nama_rek').val(data.nama_rek);
                $('#no_rek').val(data.no_rek);
                $('#jenis_rek').val(data.jenis_rek);
                $('#isActive').val(data.isActive);

        getAkun(data.akun_id);
            })

        });

        $('body').on('click', '.deleteRekening', function() {
            var id_rekening = $(this).data("id_rekening");
            status = $(this).data("status");

            myurl = '';
            if (status == 'trash') {
                myurl = "{{ url('/rekening-trash') }}" + '/' + id_rekening
                msg =
                    "Data Rekening yang dihapus akan dipindahkan ke Tempat Sampah!";
            } else if (status == 'delete') {
                myurl = "{{ url('/rekening-delete') }}" + '/' + id_rekening
                msg =
                    "Data Rekening yang dihapus tidak dapat dikembalikan!";
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
                                    table.draw();
                                    tableTrashed.draw();
                                    $("#canvasloading").hide();
                                    $("#loading").hide();
                                    $.NotificationApp.send("Berhasil",
                                        data.message, "top-right", "",
                                        "info")
                                } else {
                                    table.draw();
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


        $('body').on('click', '.restoreRekening', function() {

            var id_rekening = $(this).data("id_rekening");
            swal({
                    title: "Kembalikan Rekening ini?",
                    text: "Data akan dikembalikan ke tabel Rekening!",
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
                            url: "{{ url('/rekening-restore') }}" + '/' + id_rekening,
                            success: function(data) {
                                table.draw();
                                tableTrashed.draw();
                                $("#canvasloading").hide();
                                $("#loading").hide();
                                $.NotificationApp.send("Berhasil",
                                    data.message, "top-right", "",
                                    "info")
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
                                url: "{{ url('/rekening/bulk-delete') }}",
                                method: "get",
                                data: {
                                    id: id
                                },
                                success: function(data) {
                                    if (data.success == true) {
                                        table.draw();
                                        tableTrashed.draw();
                                        $("#canvasloading").hide();
                                        $("#loading").hide();
                                        $.NotificationApp.send("Berhasil",
                                            data.message, "top-right", "",
                                            "info")
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

        // $('.chosen-select').chosen({width: "100%"});
    });
</script>
