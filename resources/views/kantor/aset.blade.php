@extends("layouts.app")

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">

                <h4 class="page-title">Aset</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-body">
@can('aset','aset-create')
                    <button type="button" id="createNewAset" class="btn btn-primary mb-2" data-bs-toggle="modal"
                        data-bs-target="#success-header-modal"><i class="mdi mdi-plus-circle me-2"></i> Tambah</button>
   @endcan
                        <form action="/print-laporan-aset" target="_blank" method="post">
                        @csrf
                                <h4>Filter</h4>
                                <div class="row">

                                    <div class="col align-self-start">
                                        <label for="kondisi">Kondisi Aset</label>
                                        <select name="kondisi" id="kondisi" class="form-control">
                                            <option value="">Semua</option>
                                            <option value="Baik">Baik</option>
                                            <option value="Sedang">Sedang</option>
                                            <option value="Rusak">Rusak</option>
                                        </select>
                                    </div>
                                    <div class="col align-self-center"></div>
                                    <div class="col align-self-end">
                                        <label for="">Aksi</label>
                                        <div class="form-group">
                                            <a id="filter" class="btn btn-outline-primary mb-2">Filter</a>
                                            <a id="refresh" class="btn btn-outline-info mb-2">Refresh</a><br>
                                            <input type="submit" name="print" value="Print" class="btn btn-outline-success">
                                            <input type="submit" name="export" value="Excel" class="btn btn-outline-warning">
                                            <input type="submit" name="pdf" value="PDF" class="btn btn-outline-danger">
                                        </div>
                                    </div>
                                </div>
                    </form>
                    <br />
                    <div class="tab-content">
                        <div class="tab-pane show active" id="state-saving-preview">
                            <table id="datatable" class="table dt-responsive  table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Aset</th>
                                        <th>Kode Aset</th>
                                        <th>Merek</th>
                                        <th>Satuan</th>
                                        <th>Jumlah</th>
                                        <th>Kondisi</th>
                                        <th>Gambar</th>
                                        <th>Deskripsi</th>
                                        <th>Aksi</th>
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

    <div id="ajaxModel" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="success-header-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-info">
                    <h4 class="modal-title" id="success-header-modalLabel">Aset</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form id="asetForm" name="asetForm" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id_aset" id="id_aset">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-2 aset-group">
                                    <label for="kode_aset" class="control-label">Kode Aset <span
                                            style="color: red;">*</span></label>

                                    <input type="text" class="form-control" id="kode_aset" name="kode_aset" required="">

                                </div>
                                <div class="mb-2 aset-group">
                                    <label for="nama_aset" class="control-label">Nama Aset <span
                                            style="color: red;">*</span></label>

                                    <input type="text" class="form-control" id="nama_aset" name="nama_aset"
                                        placeholder="Masukkan Nama Aset" required="">

                                </div>
                                <div class="mb-2 aset-group">
                                    <label for="merek" class="control-label">Merek </label>

                                    <input type="text" class="form-control" id="merek" name="merek" required="">

                                </div>
                                <div class="mb-2 aset-group">
                                    <label for="satuan" class="control-label">Satuan <span
                                            style="color: red;">*</span></label>

                                    <input type="text" name="satuan" id="satuan" class="form-control">

                                </div>
                                <div class="mb-2 aset-group">
                                    <label for="jumlah" class="control-label">Jumlah <span
                                            style="color: red;">*</span></label>

                                    <input type="number" name="jumlah" id="jumlah" class="form-control">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2 aset-group">
                                    <label for="kondisi" class="control-label">Kondisi <span
                                            style="color: red;">*</span></label>
                                    <select name="kondisi" id="kondisi" class="form-control">
                                        <option value="Baik">Baik</option>
                                        <option value="Sedang">Sedang</option>
                                        <option value="Rusak">Rusak</option>
                                    </select>
                                </div>
                                <div class="mb-2 aset-group">
                                    <label for="deskripsi" class="control-label">Deskripsi</label>

                                    <textarea name="deskripsi" id="deskripsi" class="form-control"></textarea>

                                </div>
                                <div class="mb-2">
                                    <label for="file" class="control-label">Pilih Gambar</label>

                                    <input type="file" class="form-control" id="file" name="file" required="">
                                    <small>Hanya diperbolehkan dengan extensi : <b style="color: darkred;"> .ico / .png /
                                            .jpg / .jpeg</b> |
                                        Maksimal Ukuran 1MB</small>
                                    <div class="mb-2">
                                        <img id="preview-image-before-upload" alt="Preview Image"
                                            style="max-height: 100px; max-width:350px">
                                    </div>
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

            $('.input-daterange').datepicker({
                todayBtn: 'linked',
                format: 'yyyy-mm-dd',
                autoclose: true
            });

            // datatable
            load_data();

            function load_data(kondisi = '') {
                var table = $('#datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    retrieve: true,
                    paging: true,
                    destroy: true,
                    scrollX: false,
                    ajax: {
                        url: "{{ url('/asetTable') }}",
                        type: "POST",
                        data: {
                            kondisi: kondisi,
                        }

                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'nama_aset',
                            name: 'nama_aset'
                        },
                        {
                            data: 'kode_aset',
                            name: 'kode_aset'
                        },
                        {
                            data: 'merek',
                            name: 'merek'
                        },
                        {
                            data: 'satuan',
                            name: 'satuan'
                        },
                        {
                            data: 'jumlah',
                            name: 'jumlah'
                        },
                        {
                            data: 'kondisi',
                            name: 'kondisi'
                        },
                        {
                            data: 'gambar',
                            name: 'gambar'
                        },
                        {
                            data: 'deskripsi',
                            name: 'deskripsi'
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

            $('#filter').click(function() {
                var kondisi = $('#kondisi').val();

                    $('#datatable').DataTable().destroy();
                    load_data(kondisi);
                    $("#print").val('Print');
                    $("#export").val('Export');

            });

            $('#refresh').click(function() {
                $('#kondisi').val('');
                $('#datatable').DataTable().destroy();
                load_data();
            });

            $('.select2').select2({
                dropdownParent: $('#ajaxModel')
            });

            function kode_aset() {
            $.ajax({
                url: "{{ url('/aset/kode') }}",
                type: "get",
                dataType: 'json',
                success: function(data) {
                    $("#kode_aset").val(data.data);
                },
            });
        }

            $('#createNewAset').click(function() {
                $('#saveBtn').html("Simpan");
                $('#id_aset').val('');
                $('#asetForm').trigger("reset");
                kode_aset();
                $('#modelHeading').html("Tambah Aset ");
                $('#ajaxModel').modal('show');
            });

            $('#saveBtn').click(function(e) {
                e.preventDefault();
                $("#canvasloading").show();
                $("#loading").show();
                $(this).html('Menyimpan..');

                var form = $('#asetForm')[0];
                var formData = new FormData(form);
                $.ajax({
                    data: formData,
                    url: "{{ url('/aset/simpan') }}",
                    type: "POST",
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.success == true) {

                            $('#asetForm').trigger("reset");
                            $('#ajaxModel').modal('hide');
                            $('#datatable').DataTable().destroy();
                            load_data();
                            $('#saveBtn').html('Simpan');
                            $("#canvasloading").hide();
                            $("#loading").hide();
                            swal("Pesan", data.message, "success");
                        } else {
                            $('#saveBtn').html('Simpan');
                            $("#canvasloading").hide();
                            $("#loading").hide();
                            swal("Pesan", data.message, "error");
                        }
                    },
                    error: function(xhr) {
                        var res = xhr.responseJSON;
                        if ($.isEmptyObject(res) == false) {
                            err = '';
                            $.each(res.errors, function(key, value) {
                                // err += value + ', ';
                                err = value;
                            });
                            $('#saveBtn').html('Simpan');
                            $("#canvasloading").hide();
                            $("#loading").hide();
                            swal("Pesan", err, "error");
                        }
                    }
                });
            });

            $('body').on('click', '.editAset', function() {
                $("#canvasloading").show();
                $("#loading").show();
                var id_aset = $(this).data('id_aset');
                $.get("{{ url('/aset') }}" + '/' + id_aset + '/edit', function(data) {
                    $("#canvasloading").hide();
                    $("#loading").hide();
                    $('#modelHeading').html("Ubah Aset ");
                    $('#saveBtn').html('Perbaharui');
                    $('#ajaxModel').modal('show');
                    $('#id_aset').val(data.id);
                    $('#kode_aset').val(data.kode_aset);
                    $('#nama_aset').val(data.nama_aset);
                    $('#merek').val(data.merek);
                    $('#satuan').val(data.satuan);
                    $('#jumlah').val(data.jumlah);
                    $('#kondisi').val(data.kondisi);
                    $('#deskripsi').val(data.deskripsi);


                })

            });

            $('body').on('click', '.deleteAset', function() {

                var id_aset = $(this).data("id_aset");
                swal({
                        title: "Yakin hapus data ini?",
                        text: "Data yang sudah dihapus tidak dapat dikembalikan!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Ya, Hapus Data!",
                        cancelButtonText: "Batal!",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            $("#canvasloading").show();
                            $("#loading").show();
                            $.ajax({
                                type: "DELETE",
                                url: "{{ url('/aset') }}" + '/' + id_aset,
                                success: function(data) {

                                    $('#datatable').DataTable().destroy();
                                    load_data();
                                    $("#canvasloading").hide();
                                    $("#loading").hide();
                                    swal("Deleted!", "Data Berhasil Dihapus...!",
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
                            swal("Cancelled", "Hapus data dibatalkan...! :)", "error");
                        }
                    });
            });




        });
    </script>
@endsection
