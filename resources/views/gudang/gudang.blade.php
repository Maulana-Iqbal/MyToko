@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">

            <h4 class="page-title">Gudang</h4>
        </div>
    </div>
</div>
<ul class="nav nav-tabs nav-bordered mb-3">
    <li class="nav-item">
        <a href="#dataGudang" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
            <i class="mdi mdi-table d-md-none d-block"></i>
            <span class="d-none d-md-block">Gudang</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="#dataTrash" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
            <i class="mdi mdi-delete d-md-none d-block"></i>
            <span class="d-none d-md-block">Sampah</span>
        </a>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane show active" id="dataGudang">
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-header">
                        @if(auth()->user()->can('gudang') or auth()->user()->can('gudang-create'))
                        <button type="button" id="createNewGudang" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#success-header-modal"><i class="mdi mdi-plus-circle me-2"></i>
                            Tambah</button>
                        @endif
                        @if(auth()->user()->can('gudang') or auth()->user()->can('gudang-trash'))
                        <button type="button" name="bulk_delete" id="bulk_delete" class="btn btn-outline-danger float-end">Hapus
                            Pilihan</button>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable" class="table table-centered table-bordered table-hover w-100 dt-responsive nowrap dataTable no-footer dtr-inline">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th class="all" style="width: 20px;">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="master">
                                                <label class="form-check-label" for="master">&nbsp;</label>
                                            </div>
                                        </th>
                                        <th>Kode</th>
                                        <th>Nama Gudang</th>
                                        <th>Alamat</th>
                                        <th>Jenis</th>
                                        <th>Status</th>
                                        <th>Admin</th>
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
    </div>
    <div class="tab-pane" id="dataTrash">
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatableTrash" class="table table-centered w-100 dt-responsive  table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Nama Gudang</th>
                                        <th>Alamat</th>
                                        <th>Jenis</th>
                                        <th>Status</th>
                                        <th>Admin</th>
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
    </div>
</div>
<!-- Success Header Modal -->

<div id="ajaxModel" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="success-header-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-info">
                <h4 class="modal-title" id="success-header-modalLabel">Gudang</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form id="gudangForm" name="gudangForm" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id_gudang" id="id_gudang">
                    <div class="mb-2">
                        <label for="user" class="control-label">Admin Gudang <span style="color: red;">*</span></label>
                        <a target="_blank" href="/user"><small class="text-primary float-end">Buat Pengguna Baru</small></a>
                        <select id="user" name="user" required class="form-control select2">
                        </select>
                    </div>
                    <div class="mb-2 gudang-group">
                        <label for="kode" class="col-sm-3 control-label">Kode Gudang<span style="color: red;">*</span></label>

                        <input type="text" class="form-control" id="kode" name="kode" placeholder="Masukkan Kode Gudang" required="">
                    </div>

                    <div class="mb-2 gudang-group">
                        <label for="nama" class="col-sm-3 control-label">Nama Gudang <span style="color: red;">*</span></label>

                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan Nama Gudang" required="">
                    </div>

                    <div class="mb-2 gudang-group">
                        <label for="jenis" class="col-sm-3 control-label">Jenis <span style="color: red;">*</span></label>
                        <select name="jenis" id="jenis" class="form-control">
                            <option value="">Pilih Jenis</option>
                            <option value="1">Inventory</option>
                            <option value="2">Factory</option>
                        </select>
                    </div>

                    <div class="mb-2 gudang-group">
                        <label for="aktif" class="col-sm-3 control-label">Status <span style="color: red;">*</span></label>
                        <select name="aktif" id="aktif" class="form-control">
                            <option value="">Pilih Status</option>
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>

                    <div class="mb-2 gudang-group">
                        <label for="alamat" class="col-sm-3 control-label">Alamat Gudang <span style="color: red;">*</span></label>
                        <textarea name="alamat" id="alamat" class="form-control"></textarea>
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
                url: "{{ url('/gudangTable') }}",
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
                    data: 'kode',
                    name: 'kode'
                },
                {
                    data: 'nama',
                    name: 'nama'
                },
                {
                    data: 'alamat',
                    name: 'alamat'
                },
                {
                    data: 'jenis',
                    name: 'jenis'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'user',
                    name: 'user'
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
                url: "{{ url('/gudangTableTrash') }}",
                type: "POST",

            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'kode',
                    name: 'kode'
                },
                {
                    data: 'nama',
                    name: 'nama'
                },
                {
                    data: 'alamat',
                    name: 'alamat'
                },
                {
                    data: 'jenis',
                    name: 'jenis'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'user',
                    name: 'user'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });


        $("#user").select2({
            // placeholder: 'Pilih User',
            allowClear: true,
            dropdownParent: $('#ajaxModel .modal-body'),
            ajax: {
                url: "/user/select",
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

        $('#master').on('click', function(e) {
            if ($(this).is(':checked', true)) {
                $(".select").prop('checked', true);
            } else {
                $(".select").prop('checked', false);
            }
        });


        function kode_gudang() {
            $.ajax({
                url: "{{ url('/gudang/kode') }}",
                type: "get",
                dataType: 'json',
                success: function(data) {
                    $("#kode").val(data.data);
                },
            });
        }


        $('#createNewGudang').click(function() {
            $('#saveBtn').html("Simpan");
            $('#id_gudang').val('');
            $('#gudangForm').trigger("reset");
            $("#user").val('').trigger('change');
            $('#modelHeading').html("Tambah Gudang ");
            kode_gudang();
            $('#ajaxModel').modal('show');
        });

        $('#saveBtn').click(function(e) {
            e.preventDefault();

            id = $('#id_gudang').val();


            $(this).html('Menyimpan..');

            var form = $('#gudangForm')[0];
            var formData = new FormData(form);
            $("#canvasloading").show();
            $("#loading").show();
            $.ajax({
                data: formData,
                url: "{{ url('/gudang/simpan') }}",
                type: "POST",
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data.success == true) {

                        $('#gudangForm').trigger("reset");
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

        });

        $('body').on('click', '.editGudang', function() {
            $("#user").select2("trigger", "select", {
                    data: {
                        id: '',
                        text: ''
                    }
                });
            var id_gudang = $(this).data('id_gudang');
            $("#canvasloading").show();
            $("#loading").show();
            $.get("{{ url('/gudang') }}" + '/' + id_gudang + '/edit', function(data) {
                $("#canvasloading").hide();
                $("#loading").hide();
                $('#modelHeading').html("Ubah Gudang ");
                $('#saveBtn').html('Perbaharui');
                $('#ajaxModel').modal('show');
                $('#id_gudang').val(data.id);
                $('#kode').val(data.kode);
                $('#nama').val(data.nama);
                $('#jenis').val(data.jenis);
                $('#aktif').val(data.status);
                $('#alamat').val(data.alamat);
                $("#user").select2("trigger", "select", {
                    data: {
                        id: data.user_id,
                        text: data.user.name
                    }
                });

            })

        });

        $('body').on('click', '.deleteGudang', function() {
            var id_gudang = $(this).data("id_gudang");
            status = $(this).data("status");

            myurl = '';
            if (status == 'trash') {
                myurl = "{{ url('/gudang-trash') }}" + '/' + id_gudang
                msg =
                    "Data Gudang yang dihapus akan dipindahkan ke Tempat Sampah!";
            } else if (status == 'delete') {
                myurl = "{{ url('/gudang-delete') }}" + '/' + id_gudang
                msg =
                    "Data Gudang yang dihapus tidak dapat dikembalikan!";
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


        $('body').on('click', '.restoreGudang', function() {

            var id_gudang = $(this).data("id_gudang");
            swal({
                    title: "Kembalikan Gudang ini?",
                    text: "Data akan dikembalikan ke tabel Gudang!",
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
                            url: "{{ url('/gudang-restore') }}" + '/' + id_gudang,
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
                                url: "{{ url('/gudang/bulk-delete') }}",
                                method: "POST",
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
@endsection