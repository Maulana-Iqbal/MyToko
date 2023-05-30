@extends("layouts.app")

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">

                <h4 class="page-title">Menu</h4>
            </div>
        </div>
    </div>
    <ul class="nav nav-tabs nav-bordered mb-3">
        <li class="nav-item">
            <a href="#dataMenu" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
                <i class="mdi mdi-home-variant d-md-none d-block"></i>
                <span class="d-none d-md-block">Menu</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="#dataTrash" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                <i class="mdi mdi-account-circle d-md-none d-block"></i>
                <span class="d-none d-md-block">Trash</span>
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane show active" id="dataMenu">
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-body">
                            {{-- @if (auth()->user()->level == 'STAFF' or auth()->user()->level == 'ADMIN') --}}
                                <div class="mb-2">
                                    <button type="button" id="createNewMenu" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#success-header-modal"><i class="mdi mdi-plus-circle me-2"></i>
                                        Tambah</button>
                                    <button type="button" name="bulk_delete" id="bulk_delete"
                                        class="btn btn-danger float-end">Hapus Pilihan</button>
                                </div>
                            {{-- @endif --}}
                            <div class="table-responsive">
                                <table id="datatable" class="table table-centered w-100 dt-responsive nowrap table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th><input type="checkbox" id="master"></th>
                                            <th>Menu Title</th>
                                            <th>Index</th>
                                            <th>Url</th>
                                            <th>Icon</th>
                                            <th>Action</th>
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
                                <table id="datatableTrash"
                                    class="table table-centered w-100 dt-responsive nowrap table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th class="all">Menu Title</th>
                                            <th>Index</th>
                                            <th>Url</th>
                                            <th>Icon</th>
                                            <th>Action</th>
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

    <div id="ajaxModel" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="success-header-modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-success">
                    <h4 class="modal-title" id="success-header-modalLabel">Menu</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form id="menuForm" name="menuForm" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id_menu" id="id_menu">

                        <div class="mb-2 produk-group">
                            <label for="parent" class="control-label">Parent</label>

                            <!-- Single Select -->
                            <select id="induk" name="induk" class="form-control" >
                                <option value="">Parent</option>
                                @foreach ($menu as $p)
                                    <option value="{{ $p->id }}">{{ $p->title }}</option>
                                @endforeach
                                </optgroup>
                            </select>

                        </div>

                        <div class="mb-2 produk-group">
                            <label for="indek" class="control-label">Urutan</label>

                            <!-- Single Select -->
                            <select id="indek" name="indek" class="form-control" >
                                <option value="">Urutan</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                
                                </optgroup>
                            </select>

                        </div>

                        <div class="mb-2 menu-group">
                            <label for="title" class="col-sm-3 control-label">Menu Title <span
                                    style="color: red;">*</span></label>

                            <input type="text" class="form-control" id="title" name="title" placeholder="Menu Title"
                                required="">

                        </div>

                        <div class="mb-2 menu-group">
                            <label for="url" class="col-sm-3 control-label">URL</label>

                            <input type="text" class="form-control" id="url" name="url" placeholder="https://url">

                        </div>

                        <div class="mb-2">
                            <label for="file" class="control-label">Pilih Icon</label>

                            <input type="file" class="form-control" id="file" name="file">
                            <small>Hanya diperbolehkan dengan extensi : <b style="color: darkred;"> .ico / .png / .jpg /
                                    .jpeg</b> |
                                Maksimal Ukuran 200KB</small>
                            <div class="mb-2">
                                <img id="preview-image-before-upload" alt="Preview Image"
                                    style="max-height: 100px; max-width:350px">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="saveBtn">Save</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->



    <script type="text/javascript">
        $(document).ready(function(e) {


            $('#file').change(function() {

                let reader = new FileReader();

                reader.onload = (e) => {

                    $('#preview-image-before-upload').attr('src', e.target.result);
                }

                reader.readAsDataURL(this.files[0]);

            });

        });


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
                    url: "{{ url('/menuTable') }}",
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
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'indek',
                        name: 'indek'
                    },
                    {
                        data: 'url',
                        name: 'url'
                    },
                    {
                        data: 'icon',
                        name: 'icon'
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
                    url: "{{ url('/menuTableTrash') }}",
                    type: "POST",

                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'indek',
                        name: 'indek'
                    },
                    {
                        data: 'url',
                        name: 'url'
                    },
                    {
                        data: 'icon',
                        name: 'icon'
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




            $('#createNewMenu').click(function() {
                $('#saveBtn').html("Save");
                $('#id_menu').val('');
                $('#menuForm').trigger("reset");
                $('#modelHeading').html("Create Menu ");
                $('#ajaxModel').modal('show');
            });

            $('#saveBtn').click(function(e) {
                e.preventDefault();
                nama_menu = $("#nama_menu").val();
                id = $('#id_menu').val();
                file = $("#file").val();
                if (nama_menu == '') {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Title Is Required!',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    })
                }else {


                    $(this).html('Saving..');

                    var form = $('#menuForm')[0];
                    var formData = new FormData(form);
                    $("#canvasloading").show();
                    $("#loading").show();
                    $.ajax({
                        data: formData,
                        url: "{{ url('/menu/simpan') }}",
                        type: "POST",
                        dataType: 'json',
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function(data) {
                            if (data.success == true) {

                                $('#menuForm').trigger("reset");
                                $('#ajaxModel').modal('hide');
                                table.draw();
                                $('#saveBtn').html('Save');
                                $("#canvasloading").hide();
                                $("#loading").hide();
                                Swal.fire({
                                    title: 'Success!',
                                    text: 'Data Saving Success!',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                })
                            } else {
                                $('#saveBtn').html('Save');
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
                                $('#saveBtn').html('Save');
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

            $('body').on('click', '.editMenu', function() {

                var id_menu = $(this).data('id_menu');
                $("#canvasloading").show();
                $("#loading").show();
                $.get("{{ url('/menu') }}" + '/' + id_menu + '/edit', function(data) {
                    $("#canvasloading").hide();
                    $("#loading").hide();
                    $('#modelHeading').html("Edit Menu ");
                    $('#saveBtn').html('Update');
                    $('#ajaxModel').modal('show');
                    $('#id_menu').val(data.id);
                    $('#title').val(data.title);
                    $('#url').val(data.url);
                    $('#induk').val(data.induk);
                    $('#indek').val(data.indek);
                })

            });

            $('body').on('click', '.deleteMenu', function() {
                var id_menu = $(this).data("id_menu");
                status = $(this).data("status");

                myurl = '';
                if (status == 'trash') {
                    myurl = "{{ url('/menu/trash') }}" + '/' + id_menu
                    msg =
                        "Move to trash!";
                } else if (status == 'delete') {
                    myurl = "{{ url('/menu/delete') }}" + '/' + id_menu
                    msg =
                        "Permanen Deleted!";
                }
                swal({
                        title: "Are you sure delete it??",
                        text: msg,
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, Delete Data!",
                        cancelButtonText: "Cancel!",
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
                                    table.draw();
                                    tableTrashed.draw();
                                    $("#canvasloading").hide();
                                    $("#loading").hide();
                                    Swal.fire({
                                        title: 'Deleted!',
                                        text: 'Data Deleted Success',
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    })
                                },
                                error: function(data) {
                                    console.log('Error:', data);
                                    $("#canvasloading").hide();
                                    $("#loading").hide();
                                    Swal.fire({
                                        title: 'Error!',
                                        text: 'Problem',
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    })
                                }
                            });

                        } else {
                            $("#canvasloading").hide();
                            $("#loading").hide();
                            Swal.fire({
                                title: 'Info!',
                                text: 'Deleted Data Cancel',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            })
                        }
                    });
            });


            $('body').on('click', '.restoreMenu', function() {

                var id_menu = $(this).data("id_menu");
                swal({
                        title: "Restore menu?",
                        text: "",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, Restore!",
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
                                url: "{{ url('/menu/restore') }}" + '/' + id_menu,
                                success: function(data) {
                                    table.draw();
                                    tableTrashed.draw();
                                    $("#canvasloading").hide();
                                    $("#loading").hide();
                                    swal("Success!", "Data Restore Success...!",
                                        "success");
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
                            swal("Cancelled", "Data Restore Failed! :)", "error");
                        }
                    });
            });

            $(document).on('click', '#bulk_delete', function() {
                var id = [];
                swal({
                        title: "Yakin hapus data yang dipilih?",
                        text: "Data yang dihapus akan dipindahkan ke Tempat Sampah termasuk data Produk dan Stock dari Menu ini!",
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
                                    url: "{{ url('/menu/bulk-delete') }}",
                                    method: "get",
                                    data: {
                                        id: id
                                    },
                                    success: function(data) {
                                        table.draw();
                                        tableTrashed.draw();
                                        $("#canvasloading").hide();
                                        $("#loading").hide();
                                        Swal.fire({
                                            title: 'Deleted!',
                                            text: 'Data Berhasil Dihapus',
                                            icon: 'success',
                                            confirmButtonText: 'OK'
                                        })
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
