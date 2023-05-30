@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">

            <h4 class="page-title">Supplier</h4>
        </div>
    </div>
</div>
<ul class="nav nav-tabs nav-bordered mb-3">
    <li class="nav-item">
        <a href="#dataPemasok" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
        <i class="mdi mdi-table d-md-none d-block"></i>
            <span class="d-none d-md-block">Supplier</span>
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
    <div class="tab-pane show active" id="dataPemasok">
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-body">

                        <div class="mb-2">
                            
                            @if(auth()->user()->can('supplier') or auth()->user()->can('supplier-create'))
                            <button type="button" id="createNewPemasok" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#success-header-modal"><i class="mdi mdi-plus-circle me-2"></i>
                                Tambah</button>
                            @endif
                            
                            @if(auth()->user()->can('supplier') or auth()->user()->can('supplier-trash'))
                            <button type="button" name="bulk_delete" id="bulk_delete" class="btn btn-outline-danger float-end">Hapus
                                Pilihan</button>
                            @endif
                        </div>
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
                                        <th>Nama Supplier</th>
                                        <th>Perusahaan</th>
                                        <th>Email</th>
                                        <th>Telpon</th>
                                        <th>Alamat</th>
                                        <th>Status</th>
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
                                        <th class="all">Nama Supplier</th>
                                        <th>Perusahaan</th>
                                        <th>Email</th>
                                        <th>Telpon</th>
                                        <th>Alamat</th>
                                        <th>Status</th>
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
                <h4 class="modal-title" id="success-header-modalLabel">Supplier</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form id="pemasokForm" name="pemasokForm" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id_pemasok" id="id_pemasok">

                    <div class="mb-2 pemasok-group">
                        <label for="kode" class="col-sm-3 control-label">Kode Supplier <span style="color: red;">*</span></label>

                        <input type="text" class="form-control" id="kode" name="kode" placeholder="Masukkan Kode Supplier" required="">

                    </div>

                    <div class="mb-2 pemasok-group">
                        <label for="nama" class="col-sm-3 control-label">Nama Supplier <span style="color: red;">*</span></label>

                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan Nama Supplier" required="">

                    </div>

                    <div class="mb-2 pemasok-group">
                        <label for="perusahaan" class="col-sm-3 control-label">Nama Perusahaan</label>

                        <input type="text" class="form-control" id="perusahaan" name="perusahaan" placeholder="Masukkan Nama Perusahaan" required="">

                    </div>


                    <div class="mb-2 pemasok-group">
                        <label for="email" class="col-sm-3 control-label">Email</label>

                        <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan Email Supplier">

                    </div>


                    <div class="mb-2 pemasok-group">
                        <label for="telepon" class="col-sm-3 control-label">Telpon <span style="color: red;">*</span></label>

                        <input type="text" class="form-control" id="telepon" name="telepon" placeholder="Masukkan Telpon Supplier" required="">

                    </div>

                    <div class="mb-2 pemasok-group">
                        <label for="aktif" class="col-sm-3 control-label">Status <span style="color: red;">*</span></label>
                        <select name="aktif" id="aktif" class="form-control">
                            <option value="">Pilih Status</option>
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>

                    <div class="mb-2 pemasok-group">
                        <label for="alamat" class="col-sm-3 control-label">Alamat Lengkap <span style="color: red;">*</span></label>
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
                url: "{{ url('/pemasokTable') }}",
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
                    data: 'perusahaan',
                    name: 'perusahaan'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'telepon',
                    name: 'telepon'
                },
                {
                    data: 'alamat',
                    name: 'alamat'
                },
                {
                    data: 'aktif',
                    name: 'aktif'
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
                url: "{{ url('/pemasokTableTrash') }}",
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
                    data: 'nama',
                    name: 'nama'
                },
                {
                    data: 'perusahaan',
                    name: 'perusahaan'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'telepon',
                    name: 'telepon'
                },
                {
                    data: 'alamat',
                    name: 'alamat'
                },
                {
                    data: 'aktif',
                    name: 'aktif'
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





        $('#createNewPemasok').click(function() {
            $('#saveBtn').html("Simpan");
            $('#id_pemasok').val('');
            $('#pemasokForm').trigger("reset");
            $('#modelHeading').html("Tambah Supplier ");
            kode_supplier();
            $('#ajaxModel').modal('show');
        });

        $('#saveBtn').click(function(e) {
            e.preventDefault();

            id = $('#id_pemasok').val();


            $(this).html('Menyimpan..');

            var form = $('#pemasokForm')[0];
            var formData = new FormData(form);
            $("#canvasloading").show();
            $("#loading").show();
            $.ajax({
                data: formData,
                url: "{{ url('/pemasok/simpan') }}",
                type: "POST",
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data.success == true) {

                        $('#pemasokForm').trigger("reset");
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

        $('body').on('click', '.editPemasok', function() {

            var id_pemasok = $(this).data('id_pemasok');
            $("#canvasloading").show();
            $("#loading").show();
            $.get("{{ url('/pemasok') }}" + '/' + id_pemasok + '/edit', function(data) {
                $("#canvasloading").hide();
                $("#loading").hide();
                $('#modelHeading').html("Ubah Supplier ");
                $('#saveBtn').html('Perbaharui');
                $('#ajaxModel').modal('show');
                $('#id_pemasok').val(data.id);
                $('#kode').val(data.kode);
                $('#nama').val(data.nama);
                $('#perusahaan').val(data.perusahaan);
                $('#email').val(data.email);
                $('#telepon').val(data.telepon);
                $('#alamat').val(data.alamat);
                $('#aktif').val(data.aktif);

            })

        });

        $('body').on('click', '.deletePemasok', function() {
            var id_pemasok = $(this).data("id_pemasok");
            status = $(this).data("status");

            myurl = '';
            if (status == 'trash') {
                myurl = "{{ url('/pemasok-trash') }}" + '/' + id_pemasok
                msg =
                    "Data Supplier yang dihapus akan dipindahkan ke Tempat Sampah!";
            } else if (status == 'delete') {
                myurl = "{{ url('/pemasok-delete') }}" + '/' + id_pemasok
                msg =
                    "Data Supplier yang dihapus tidak dapat dikembalikan!";
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


        $('body').on('click', '.restorePemasok', function() {

            var id_pemasok = $(this).data("id_pemasok");
            swal({
                    title: "Kembalikan Supplier ini?",
                    text: "Data akan dikembalikan ke tabel Supplier!",
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
                            url: "{{ url('/pemasok-restore') }}" + '/' + id_pemasok,
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
                                url: "{{ url('/pemasok/bulk-delete') }}",
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

        function kode_supplier() {
            $.ajax({
                url: "{{ url('/supplier/kode') }}",
                type: "get",
                dataType: 'json',
                success: function(data) {
                    $("#kode").val(data.data);
                },
            });
        }

        // $('.chosen-select').chosen({width: "100%"});
    });
</script>
@endsection