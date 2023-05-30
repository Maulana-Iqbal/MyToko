@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box">

            <h4 class="page-title">Permission</h4>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-12">

        <div class="card">
            <div class="card-header">
                @if(auth()->user()->can('permission') or auth()->user()->can('permission-create'))
                <button type="button" id="createNewPermission" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#success-header-modal"><i class="mdi mdi-plus-circle me-2"></i>
                    Tambah</button>
                @endif

                @if(auth()->user()->can('permission') or auth()->user()->can('permission-delete')) <button type="button" name="bulk_delete" id="bulk_delete" class="btn btn-outline-danger float-end">Hapus
                    Pilihan</button>
                @endif
            </div>
            <div class="card-body">


                <div class="table-responsive">
                    <table id="datatable" class="table table-centered table-bordered table-hover w-100 dt-responsive nowrap dataTable no-footer dtr-inline">
                        <thead class="table-light">
                            <tr>
                                <th width="20px">No</th>
                                <th class="all" style="width: 20px;">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="master">
                                        <label class="form-check-label" for="master">&nbsp;</label>
                                    </div>
                                </th>
                                <th>Nama Permission</th>
                                <th>Group</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Header Modal -->

<div id="ajaxModel" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="success-header-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-info">
                <h4 class="modal-title" id="success-header-modalLabel">Permission</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form id="permissionForm" name="permissionForm" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id_permission" id="id_permission">
                    <div class="mb-2">
                        <label for="parent" class="control-label">Group</label>
                        <select id="induk" name="induk" class="form-control select2">
                            <option value="0">Pilih Group</option>
                            {{-- @foreach ($permission as $k)
                                    <option value="{{ $k->id }}">{{ $k->name }}</option>
                            @endforeach --}}
                            </optgroup>
                        </select>
                    </div>

                    <div class="mb-2 permission-group">
                        <label for="name" class="col-sm-3 control-label">Nama Permission <span style="color: red;">*</span></label>

                        <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan Nama Permission" required="">

                    </div>
                    <div class="mb-2">
                        <label for="keterangan">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" class="form-control"></textarea>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="saveBtn">Simpan</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



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
                url: "{{ url('/permissionTable') }}",
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
                    data: 'group',
                    name: 'group'
                },
                {
                    data: 'keterangan',
                    name: 'keterangan'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });



        permission();

        $('#master').on('click', function(e) {
            if ($(this).is(':checked', true)) {
                $(".select").prop('checked', true);
            } else {
                $(".select").prop('checked', false);
            }
        });


        function permission() {
            $.ajax({
                url: "/permission/select",
                type: "post",
                dataType: 'json',
                success: function(params) {
                    $("#induk").select2({
                        placeholder: {
                            id: '0', // the value of the option
                            text: 'Permission Group'
                        },
                        allowClear: true,
                        dropdownParent: $('#ajaxModel .modal-body'),
                        //    _token: CSRF_TOKEN,
                        data: params // search term
                    });
                },
            });
        }




        $('#createNewPermission').click(function() {
            $('#saveBtn').html("Simpan");
            $('#id_permission').val('');
            $('#permissionForm').trigger("reset");
            $('#modelHeading').html("Tambah Permission ");
            $("#induk").select2("trigger", "select", {
                data: {
                    id: 0
                }
            });
            permission();

            $('#ajaxModel').modal('show');
        });

        $('#saveBtn').click(function(e) {
            e.preventDefault();
            name = $("#name").val();
            induk = $("#induk").val();
            id = $('#id_permission').val();
            if (name == '') {
                Swal.fire({
                    title: 'Error!',
                    text: 'Nama Permission Tidak Boleh Kosong!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                })
            } else {


                $(this).html('Menyimpan..');

                var form = $('#permissionForm')[0];
                var formData = new FormData(form);
                $("#canvasloading").show();
                $("#loading").show();
                $.ajax({
                    data: formData,
                    url: "{{ url('/permission/simpan') }}",
                    type: "POST",
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.success == true) {
                            table.draw();
                            $('#permissionForm').trigger("reset");
                            $("#induk").select2("trigger", "select", {
                                data: {
                                    id: induk
                                }
                            });
                            $('#saveBtn').html("Simpan");
                            $('#id_permission').val('');
                            $("#canvasloading").hide();
                            $("#loading").hide();
                            if(id){
                                $("#induk").select2("trigger", "select", {
                                data: {
                                    id: 0
                                }
                            });
                                $('#ajaxModel').modal('hide');
                            }
                            $.NotificationApp.send("Berhasil", data.message, "top-right",
                                "",
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
            }
        });

        $('body').on('click', '.editPermission', function() {

            var id_permission = $(this).data('id_permission');
            $("#canvasloading").show();
            $("#loading").show();
            $.get("{{ url('/permission') }}" + '/' + id_permission + '/edit', function(data) {
                $("#canvasloading").hide();
                $("#loading").hide();
                $('#modelHeading').html("Ubah Permission ");
                $('#saveBtn').html('Perbaharui');
                $('#ajaxModel').modal('show');
                $('#id_permission').val(data.id);
                $('#name').val(data.name);
                $('#keterangan').val(data.keterangan);
                permission();
                $("#induk").select2("trigger", "select", {
                    data: {
                        id: data.induk_id
                    }
                });

            })

        });

        $('body').on('click', '.deletePermission', function() {
            var id_permission = $(this).data("id_permission");
            status = $(this).data("status");

            myurl = '';
            myurl = "{{ url('/permission-trash') }}" + '/' + id_permission

            msg =
                "Data Permission yang dihapus tidak dapat dikembalikan!";

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
                                    $("#canvasloading").hide();
                                    $("#loading").hide();
                                    $.NotificationApp.send("Berhasil", data.message,
                                        "top-right", "",
                                        "info")
                                } else {
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


        $('body').on('click', '.restorePermission', function() {

            var id_permission = $(this).data("id_permission");
            swal({
                    title: "Kembalikan Permission ini?",
                    text: "Data akan dikembalikan ke tabel Permission!",
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
                            url: "{{ url('/permission-restore') }}" + '/' + id_permission,
                            success: function(data) {
                                table.draw();
                                tableTrashed.draw();
                                $("#canvasloading").hide();
                                $("#loading").hide();
                                $.NotificationApp.send("Berhasil", data.message,
                                    "top-right", "",
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
                                url: "{{ url('/permission/bulk-delete') }}",
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
                                        $.NotificationApp.send("Berhasil", data.message,
                                            "top-right", "",
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
@endsection