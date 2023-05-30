
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
                lengthMenu: [
                    [25, 50, -1],
                    [25, 50, 200, "All"]
                ],
                "scrollX": false,
                ajax: {
                    url: "{{ url('/kategoriAkunTable') }}",
                    type: "POST",

                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    // {
                    //     data: 'select',
                    //     name: 'select',
                    //     orderable: false,
                    //     searchable: false
                    // },
                    {
                        data: 'kode',
                        name: 'kode'
                    },
                    {
                        data: 'name',
                        name: 'name'
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
                    url: "{{ url('/kategoriAkunTableTrash') }}",
                    type: "POST",

                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
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




            $('#createNewKategoriAkun').click(function() {
                $('#saveBtn').html("Simpan");
                $('#id_kategoriAkun').val('');
                $('#kategoriAkunForm').trigger("reset");
                $('#modelHeading').html("Tambah KategoriAkun ");
                $('#ajaxModel').modal('show');
            });

            $('#saveBtn').click(function(e) {
                e.preventDefault();
                kode = $("#kode").val();
                name = $("#name").val();
                id = $('#id_kategoriAkun').val();

                if (name == '' || kode == '') {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Data Belum Lengkap!',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    })
                } else {


                    $(this).html('Menyimpan..');

                    var form = $('#kategoriAkunForm')[0];
                    var formData = new FormData(form);
                    $("#canvasloading").show();
                    $("#loading").show();
                    $.ajax({
                        data: formData,
                        url: "{{ url('/kategoriAkun/simpan') }}",
                        type: "POST",
                        dataType: 'json',
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function(data) {
                            if (data.success == true) {

                                $('#kategoriAkunForm').trigger("reset");
                                $('#ajaxModel').modal('hide');
                                table.draw();
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

            $('body').on('click', '.editKategoriAkun', function() {

                var id_kategoriAkun = $(this).data('id');
                $("#canvasloading").show();
                $("#loading").show();
                $.get("{{ url('/kategoriAkun') }}" + '/' + id_kategoriAkun + '/edit', function(data) {
                    $("#canvasloading").hide();
                    $("#loading").hide();
                    $('#modelHeading').html("Ubah KategoriAkun ");
                    $('#saveBtn').html('Perbaharui');
                    $('#ajaxModel').modal('show');
                    $('#id_kategoriAkun').val(data.id);
                    $('#kode').val(data.kode);
                    $('#name').val(data.name);
                })

            });

            $('body').on('click', '.deleteKategoriAkun', function() {
                var id_kategoriAkun = $(this).data("id");
                status = $(this).data("status");

                myurl = '';
                if (status == 'trash') {
                    myurl = "{{ url('/kategoriAkun-trash') }}" + '/' + id_kategoriAkun
                    msg =
                        "Data KategoriAkun yang dihapus akan dipindahkan ke Tempat Sampah!";
                } else if (status == 'delete') {
                    myurl = "{{ url('/kategoriAkun-delete') }}" + '/' + id_kategoriAkun
                    msg =
                        "Data KategoriAkun yang dihapus tidak dapat dikembalikan!";
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
                                        Swal.fire({
                                            title: 'Deleted!',
                                            text: data.message,
                                            icon: 'success',
                                            confirmButtonText: 'OK'
                                        })
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


            $('body').on('click', '.restoreKategoriAkun', function() {

                var id_kategoriAkun = $(this).data("id");
                swal({
                        title: "Kembalikan KategoriAkun ini?",
                        text: "Data akan dikembalikan ke tabel KategoriAkun!",
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
                                url: "{{ url('/kategoriAkun-restore') }}" + '/' + id_kategoriAkun,
                                success: function(data) {
                                    table.draw();
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
                                    url: "{{ url('/kategoriAkun/bulk-delete') }}",
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

            // $('.chosen-select').chosen({width: "100%"});
        });
    </script>
