@extends("layouts.app")

@section("content")
<script src="//cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">

            <h4 class="page-title">Portofolio</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">

        <div class="card">
            <div class="card-body">
                @if(auth()->user()->level=='STAFF' or auth()->user()->level=='ADMIN')
                <button type="button" id="createNewPortofolio" class="btn btn-primary mb-2" data-bs-toggle="modal"
                    data-bs-target="#success-header-modal"><i class="mdi mdi-plus-circle me-2"></i> Tambah</button>
                @endif
                <div class="tab-content">
                    <div class="tab-pane show active" id="state-saving-preview">
                        <table id="datatable" class="table dt-responsive nowrap table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Aksi</th>
                                    <th>Nama Produk</th>
                                    <th>Kategori</th>
                                    <th>Gambar</th>
                                </tr>
                            </thead>

                            <tbody>

                            </tbody>
                        </table>

                    </div>
                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
</div>


<!-- Success Header Modal -->

<div id="ajaxModel" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog"
    aria-labelledby="success-header-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-info">
                <h4 class="modal-title" id="success-header-modalLabel">Portofolio</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form id="portofolioForm" name="portofolioForm" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id_portofolio" id="id_portofolio">
                    <div class="mb-2">
                        <label for="nama_kategori" class="control-label">Kategori</label>

                        <!-- Single Select -->
                        <select id="kategori" name="kategori" class="form-control select2" data-toggle="select2">
                            <option value="">Pilih Kategori</option>
                            @foreach($kategori as $k)
                            <option value="{{$k->id}}">{{$k->nama_kategori}}</option>
                            @endforeach
                        </select>

                    </div>
                    <div class="mb-2">
                        <label for="nama_produk" class="col-sm-3 control-label">Nama Produk</label>

                        <input type="text" class="form-control" id="nama_produk" name="nama_produk"
                            placeholder="Masukkan Nama Produk" required="">

                    </div>

                    <div class="mb-2">
                        <label for="file" class="control-label">Pilih Gambar</label>

                        <input type="file" class="form-control" id="file" name="file" required="">
                        <small>Hanya diperbolehkan dengan extensi : <b style="color: darkred;"> .ico / .png / .jpg /
                                .jpeg</b> |
                            Maksimal Ukuran 200KB</small>
                        <div class="mb-2">
                            <img id="preview-image-before-upload" alt="Preview Image"
                                style="max-height: 100px; max-width:350px">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="mb-2 produk-group">
                                <label for="deskripsi" class="control-label">Deskripsi</label>

                                <textarea name="deskripsi" id="deskripsi" class="form-control"></textarea>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">Simpan</button>
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

    CKEDITOR.replace('deskripsi');

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
            url: "{{ url('/portofolioTable') }}",
            type: "POST",

        },
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
            {
                data: 'nama_produk',
                name: 'nama_produk'
            },
            {
                data: 'kategori',
                name: 'kategori'
            },
            {
                data: 'gambar',
                name: 'gambar'
            },
        ]
    });

    $('.select2').select2({
        dropdownParent: $('#ajaxModel')
    });

    $('#createNewPortofolio').click(function() {
        $('#saveBtn').html("Simpan");
        $('#id_portofolio').val('');
        $('#portofolioForm').trigger("reset");
        $('#modelHeading').html("Tambah Portofolio ");
        $('#ajaxModel').modal('show');
    });

    $('#saveBtn').click(function(e) {
        e.preventDefault();
        nama_portofolio = $("#nama_portofolio").val();
        id = $('#id_portofolio').val();
        file = $("#file").val();
        if (nama_portofolio == '') {
            Swal.fire({
                title: 'Error!',
                text: 'Nama Produk Tidak Boleh Kosong!',
                icon: 'error',
                confirmButtonText: 'OK'
            })
        } else if (id == '' && file == '') {
            Swal.fire({
                title: 'Error!',
                text: 'File Gambar Tidak Boleh Kosong!',
                icon: 'error',
                confirmButtonText: 'OK'
            })
        } else {


            $(this).html('Menyimpan..');
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }

            var form = $('#portofolioForm')[0];
            var formData = new FormData(form);
            $("#canvasloading").show();
            $("#loading").show();
            $.ajax({
                data: formData,
                url: "{{ url('/portofolio/simpan')}}",
                type: "POST",
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data.success == true) {

                        $('#portofolioForm').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        table.draw();
                        $('#saveBtn').html('Simpan');
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        Swal.fire({
                            title: 'Success!',
                            text: 'Data Berhasil Disimpan!',
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
                            err += value + ', ';
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

    $('body').on('click', '.editPortofolio', function() {

        var id_portofolio = $(this).data('id_portofolio');
        $("#canvasloading").show();
        $("#loading").show();
        $.get("{{ url('/portofolio') }}" + '/' + id_portofolio + '/edit', function(data) {
            $("#canvasloading").hide();
            $("#loading").hide();
            $('#modelHeading').html("Ubah Portofolio ");
            $('#saveBtn').html('Perbaharui');
            $('#ajaxModel').modal('show');
            $('#id_portofolio').val(data.id);
            $('#nama_produk').val(data.nama_produk);
            CKEDITOR.instances['deskripsi'].setData(data.deskripsi);
            $(".select2").select2("trigger", "select", {
                data: {
                    id: data.kategori_id
                }
            });
        })

    });

    $('body').on('click', '.deletePortofolio', function() {

        var id_portofolio = $(this).data("id_portofolio");
        swal({
                title: "Yakin hapus data ini?",
                text: "Data yang sudah dihapus tidak dapat dikembalikan!",
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
                        type: "DELETE",
                        url: "{{ url('/portofolio') }}" + '/' + id_portofolio,
                        success: function(data) {
                            table.draw();
                            $("#canvasloading").hide();
                            $("#loading").hide();
                            Swal.fire({
                                title: 'Deleted!',
                                text: 'Data Berhasil Dihapus',
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
                                text: 'Terjadi Kesalahan',
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
                        text: 'Hapus Data Dibatalkan',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    })
                }
            });
    });



    // $('.chosen-select').chosen({width: "100%"});
});
</script>
@endsection