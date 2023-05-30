
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
                    url: "{{ url('/satuanTable') }}",
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
                    url: "{{ url('/satuanTableTrash') }}",
                    type: "POST",

                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
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




            $('#createNewSatuan').click(function() {
                $('#saveBtn').html("Simpan");
                $('#id_satuan').val('');
                $('#satuanForm').trigger("reset");
                $('#modelHeading').html("Tambah Satuan ");
                $('#ajaxModel').modal('show');
            });

            $('#saveBtn').click(function(e) {
                e.preventDefault();
                name = $("#name").val();
                id = $('#id_satuan').val();

                if (name == '') {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Nama Satuan Tidak Boleh Kosong!',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    })
                    return;
                }


                    $(this).html('Menyimpan..');

                    var form = $('#satuanForm')[0];
                    var formData = new FormData(form);
                    $("#canvasloading").show();
                    $("#loading").show();
                    $.ajax({
                        data: formData,
                        url: "{{ url('/satuan/simpan') }}",
                        type: "POST",
                        dataType: 'json',
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function(data) {
                            if (data.success == true) {

                                $('#satuanForm').trigger("reset");
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
                                    err = value;
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

            $('body').on('click', '.editSatuan', function() {

                var id_satuan = $(this).data('id_satuan');
                $("#canvasloading").show();
                $("#loading").show();
                $.get("{{ url('/satuan') }}" + '/' + id_satuan + '/edit', function(data) {
                    $("#canvasloading").hide();
                    $("#loading").hide();
                    $('#modelHeading').html("Ubah Satuan ");
                    $('#saveBtn').html('Perbaharui');
                    $('#ajaxModel').modal('show');
                    $('#id_satuan').val(data.id);
                    $('#name').val(data.name);

                })

            });

            $('body').on('click', '.deleteSatuan', function() {
                var id_satuan = $(this).data("id_satuan");
                status = $(this).data("status");

                myurl = '';
                if (status == 'trash') {
                    myurl = "{{ url('/satuan-trash') }}" + '/' + id_satuan
                    msg =
                        "Data Satuan yang dihapus akan dipindahkan ke Tempat Sampah!";
                } else if (status == 'delete') {
                    myurl = "{{ url('/satuan-delete') }}" + '/' + id_satuan
                    msg =
                        "Data Satuan yang dihapus tidak dapat dikembalikan!";
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


            $('body').on('click', '.restoreSatuan', function() {

                var id_satuan = $(this).data("id_satuan");
                swal({
                        title: "Kembalikan Satuan ini?",
                        text: "Data akan dikembalikan ke tabel Satuan!",
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
                                url: "{{ url('/satuan-restore') }}" + '/' + id_satuan,
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
                                    url: "{{ url('/satuan/bulk-delete') }}",
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
