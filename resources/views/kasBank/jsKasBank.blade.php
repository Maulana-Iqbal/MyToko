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
                url: "{{ url('/kasBankTable') }}",
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
                url: "{{ url('/kasBankTableTrash') }}",
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




        $('#createNewKasBank').click(function() {
            $('#saveBtn').html("Simpan");
            $('#id_kasBank').val('');
            $('#kasBankForm').trigger("reset");
            $('#modelHeading').html("Tambah KasBank ");
            $('#ajaxModel').modal('show');
        });

        $('#saveBtn').click(function(e) {
            e.preventDefault();

            id = $('#id_kasBank').val();




            $(this).html('Menyimpan..');

            var form = $('#kasBankForm')[0];
            var formData = new FormData(form);
            $("#canvasloading").show();
            $("#loading").show();
            $.ajax({
                data: formData,
                url: "{{ url('/kasBank/simpan') }}",
                type: "POST",
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data.success == true) {

                        $('#kasBankForm').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        load_data();
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

        });

        $('body').on('click', '.editKasBank', function() {

            var id_kasBank = $(this).data('id');
            $("#canvasloading").show();
            $("#loading").show();
            $.get("{{ url('/kasBank') }}" + '/' + id_kasBank + '/edit', function(data) {
                $("#canvasloading").hide();
                $("#loading").hide();
                $('#modelHeading').html("Ubah KasBank ");
                $('#saveBtn').html('Perbaharui');
                $('#ajaxModel').modal('show');
                $('#id_kasBank').val(data.id);
                $('select[name="akun"]').val(data.akun_id);
                // $('select[name="tipe"]').val(data.tipe);
                // $('select[name="induk"]').val(data.induk);
            })

        });

        $('body').on('click', '.deleteKasBank', function() {
            var id_kasBank = $(this).data("id");
            status = $(this).data("status");

            myurl = '';
            if (status == 'trash') {
                myurl = "{{ url('/kasBank-trash') }}" + '/' + id_kasBank
                msg =
                    "Data KasBank yang dihapus akan dipindahkan ke Tempat Sampah!";
            } else if (status == 'delete') {
                myurl = "{{ url('/kasBank-delete') }}" + '/' + id_kasBank
                msg =
                    "Data KasBank yang dihapus tidak dapat dikembalikan!";
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


        $('body').on('click', '.restoreKasBank', function() {

            var id_kasBank = $(this).data("id");
            swal({
                    title: "Kembalikan KasBank ini?",
                    text: "Data akan dikembalikan ke tabel KasBank!",
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
                            url: "{{ url('/kasBank-restore') }}" + '/' + id_kasBank,
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
                                url: "{{ url('/kasBank/bulk-delete') }}",
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


        $("#akun").select2({
                placeholder: {
                            id: '', // the value of the option
                            text: 'Pilih Akun'
                        },
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
                            placeholder: {
                            id: '', // the value of the option
                            text: 'Pilih'
                        },
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


        $("body").on("change","#akun",function(){

                getInduk(11);
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

                        dropdownParent: $('#ajaxModel .modal-body'),
                        allowClear: true,
                        // dropdownParent: $('#newPelanggan .modal-body'),
                        //    _token: CSRF_TOKEN,
                        data: params, // search term
                        placeholder: {
                            id: '', // the value of the option
                            text: 'Pilih'
                        },
                    });

                },
            });
        }

        // $('.chosen-select').chosen({width: "100%"});
    });
</script>
