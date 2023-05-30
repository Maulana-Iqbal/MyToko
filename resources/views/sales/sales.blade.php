@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">

            <h4 class="page-title">Sales</h4>
        </div>
    </div>
</div>
<ul class="nav nav-tabs nav-bordered mb-3">
    <li class="nav-item">
        <a href="#dataSales" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
        <i class="mdi mdi-table d-md-none d-block"></i>
            <span class="d-none d-md-block">Sales</span>
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
    <div class="tab-pane show active" id="dataSales">
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-body">

                        <div class="mb-2">
                            @if(auth()->user()->can('sales') or auth()->user()->can('sales-create'))
                            <button type="button" id="createNewSales" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#success-header-modal"><i class="mdi mdi-plus-circle me-2"></i>
                                Tambah</button>
                            @endif
                            @if(auth()->user()->can('sales') or auth()->user()->can('sales-trash'))
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
                                        <th>Nama Sales</th>
                                        <th>Jenis Kelamin</th>
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
                                        <th class="all">Nama Sales</th>
                                        <th>Jenis Kelamin</th>
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
                <h4 class="modal-title" id="success-header-modalLabel">Sales</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form id="salesForm" name="salesForm" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id_sales" id="id_sales">

                    <div class="mb-2 sales-group">
                        <label for="kode" class="col-sm-3 control-label">Kode Sales <span style="color: red;">*</span></label>

                        <input type="text" class="form-control" id="kode" name="kode" placeholder="Masukkan Kode Sales" required="">

                    </div>

                    <div class="mb-2 sales-group">
                        <label for="nama" class="col-sm-3 control-label">Nama Sales <span style="color: red;">*</span></label>

                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan Nama Sales" required="">

                    </div>

                    <div class="mb-2 sales-group">
                        <label for="jk" class="col-sm-3 control-label">Jenis Kelamin <span style="color: red;">*</span></label>
                        <select name="jk" id="jk" class="form-control">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="1">Laki - Laki</option>
                            <option value="2">Perempuan</option>
                        </select>
                    </div>

                    <div class="mb-2 sales-group">
                        <label for="email" class="col-sm-3 control-label">Email</label>

                        <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan Email Sales">

                    </div>


                    <div class="mb-2 sales-group">
                        <label for="telepon" class="col-sm-3 control-label">Telpon <span style="color: red;">*</span></label>

                        <input type="text" class="form-control" id="telepon" name="telepon" placeholder="Masukkan Telpon Sales" required="">

                    </div>

                    <div class="mb-2 sales-group">
                        <label for="aktif" class="col-sm-3 control-label">Status <span style="color: red;">*</span></label>
                        <select name="aktif" id="aktif" class="form-control">
                            <option value="">Pilih Status</option>
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>

                    <div class="mb-2 sales-group">
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
                url: "{{ url('/salesTable') }}",
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
                    data: 'jk',
                    name: 'jk'
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
                url: "{{ url('/salesTableTrash') }}",
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
                    data: 'jk',
                    name: 'jk'
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





        $('#createNewSales').click(function() {
            $('#saveBtn').html("Simpan");
            $('#id_sales').val('');
            $('#salesForm').trigger("reset");
            $('#modelHeading').html("Tambah Sales ");
            kode_sales();
            $('#ajaxModel').modal('show');
        });

        $('#saveBtn').click(function(e) {
            e.preventDefault();

            id = $('#id_sales').val();


            $(this).html('Menyimpan..');

            var form = $('#salesForm')[0];
            var formData = new FormData(form);
            $("#canvasloading").show();
            $("#loading").show();
            $.ajax({
                data: formData,
                url: "{{ url('/sales/simpan') }}",
                type: "POST",
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data.success == true) {

                        $('#salesForm').trigger("reset");
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

        $('body').on('click', '.editSales', function() {

            var id_sales = $(this).data('id_sales');
            $("#canvasloading").show();
            $("#loading").show();
            $.get("{{ url('/sales') }}" + '/' + id_sales + '/edit', function(data) {
                $("#canvasloading").hide();
                $("#loading").hide();
                $('#modelHeading').html("Ubah Sales ");
                $('#saveBtn').html('Perbaharui');
                $('#ajaxModel').modal('show');
                $('#id_sales').val(data.id);
                $('#kode').val(data.kode);
                $('#nama').val(data.nama);
                $('#jk').val(data.jk);
                $('#email').val(data.email);
                $('#telepon').val(data.telepon);
                $('#alamat').val(data.alamat);
                $('#aktif').val(data.aktif);

            })

        });

        $('body').on('click', '.deleteSales', function() {
            var id_sales = $(this).data("id_sales");
            status = $(this).data("status");

            myurl = '';
            if (status == 'trash') {
                myurl = "{{ url('/sales-trash') }}" + '/' + id_sales
                msg =
                    "Data Sales yang dihapus akan dipindahkan ke Tempat Sampah!";
            } else if (status == 'delete') {
                myurl = "{{ url('/sales-delete') }}" + '/' + id_sales
                msg =
                    "Data Sales yang dihapus tidak dapat dikembalikan!";
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


        $('body').on('click', '.restoreSales', function() {

            var id_sales = $(this).data("id_sales");
            swal({
                    title: "Kembalikan Sales ini?",
                    text: "Data akan dikembalikan ke tabel Sales!",
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
                            url: "{{ url('/sales-restore') }}" + '/' + id_sales,
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
                                url: "{{ url('/sales/bulk-delete') }}",
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

        function kode_sales() {
            $.ajax({
                url: "{{ url('/sales/kode') }}",
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