@extends("layouts.app")

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">

                <h4 class="page-title">Pembayaran</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        
                        <!-- <div class="col-sm-8">
                                <div class="text-sm-end">
                                    <button type="button" class="btn btn-success mb-2 me-1"><i
                                            class="mdi mdi-cog-outline"></i></button>
                                    <button type="button" class="btn btn-light mb-2 me-1">Import</button>
                                    <button type="button" class="btn btn-light mb-2">Export</button>
                                </div>
                            </div> -->
                        <!-- end col-->
                    </div>
                    <form action="/print-laporan-pembayaran" target="_blank" method="post">
                        @csrf

                        <h4>Filter</h4>
                        <div class="row">
                            <div class="col align-selft-start input-daterange">
                                <div class="row">
                                    <div class="col align-self-start">
                                        <label for="from_date">Dari Tanggal</label>
                                        <input type="text" name="from_date" id="from_date" class="form-control"
                                            placeholder="From Date" readonly />
                                    </div>
                                    <div class="col align-self-end">
                                        <label for="to_date input-daterange">Sampai Tanggal</label>
                                        <input type="text" name="to_date" id="to_date" class="form-control"
                                            placeholder="To Date" readonly />
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="verifikasi">Status Verifikasi</label>
                                    <select name="verifikasi" id="verifikasi" class="form-control">
                                        <option value="">Semua</option>
                                        <option value="1">Menunggu Verifikasi</option>
                                        <option value="2">Disetujui</option>
                                        <option value="3">Ditolak</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="metode_bayar">Metode Bayar</label>
                                    <select name="metode_bayar" id="metode_bayar" class="form-control">
                                        <option value="">Semua</option>
                                        <option value="1">Cash</option>
                                        <option value="2">Transfer Bank</option>
                                        <option value="3">Virtual Akun</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3">
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
                    <div class="table-responsive">
                        <table class="table table-centered w-100 dt-responsive  table-striped" id="datatable">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>No Transaksi</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Verifikasi</th>
                                    <th>Metode</th>
                                    <th>Nama Bank</th>
                                    <th>Nama Rekening</th>
                                    <th>No Rekening</th>
                                    <th>Jumlah</th>
                                    <th>Lampiran</th>
                                    <th style="width: 85px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>


                            </tbody>
                        </table>
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
    <!-- end row -->



    <!-- Success Header Modal -->

    <div id="ajaxModel" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="success-header-modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-info">
                    <h4 class="modal-title" id="success-header-modalLabel">Pembayaran</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form id="pembayaranForm" name="pembayaranForm" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id_pembayaran" id="id_pembayaran">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-2">
                                    <label for="tgl" class="form-label">Tanggal Pembayaran</label>
                                    <input class="form-control" id="tgl" type="date" name="tgl">
                                </div>
                                <div class="mb-2">
                                    <label for="jenis" class="control-label">Jenis Kas</label>
                                    <select name="jenis" id="jenis" class="form-control">
                                        <option value="">Pilih Jenis Kas</option>
                                        <option value="1">Kas Kecil</option>
                                        <option value="2">Kas Besar</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label for="judul" class="control-label">Nama Pembayaran</label>

                                    <input type="text" class="form-control" id="judul" name="judul"
                                        placeholder="Nama Pembayaran" required="">

                                </div>
                                <div class="mb-2">
                                    <label for="biaya" class="control-label">Biaya Dikeluarkan</label>

                                    <input type="text" class="form-control rupiah" id="biaya" name="biaya"
                                        placeholder="Rp. xxxxxxx" required="">

                                </div>
                                <div class="mb-2">
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


    <!-- Modal -->
    <div class="modal fade" id="modalVerifikasi" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-info">
                    <h5 class="modal-title" id="staticBackdropLabel">Verifikasi Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div> <!-- end modal header -->
                <form id="verifikasiForm">
                    @csrf
                    <input type="hidden" name="verifikasiId" id="verifikasiId">
                    <div class="modal-body">
                        <label for="deskripsi">Keterangan</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keluar</button>
                        <button type="button" class="btn btn-danger aksiBtn" data-id="2">Tolak</button>
                        <button type="button" class="btn btn-primary aksiBtn" data-id="1">Setuju</button>
                    </div> <!-- end modal footer -->
                </form>
            </div> <!-- end modal content-->
        </div> <!-- end modal dialog-->
    </div> <!-- end modal-->




    <script type="text/javascript">
        $(document).ready(function() {
            //ajax setup
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('body').on("keyup", "#biaya", function() {
                // Format mata uang.
                $('#biaya').mask('0.000.000.000', {
                    reverse: true
                });
            })

            $('.input-daterange').datepicker({
                todayBtn: 'linked',
                format: 'yyyy-mm-dd',
                autoclose: true
            });

            load_data();

            function load_data(from_date = '', to_date = '', verifikasi = '', metode_bayar = '') {
                var table = $('#datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    retrieve: true,
                    paging: true,
                    destroy: true,
                    "lengthMenu": [
                        [10, 25, 50, -1],
                        [10, 25, 50, "All"]
                    ],
                    "scrollX": false,
                    ajax: {
                        url: "{{ url('/pembayaranTable') }}",
                        type: "POST",
                        data: {
                            from_date: from_date,
                            to_date: to_date,
                            verifikasi: verifikasi,
                            metode_bayar: metode_bayar
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'kode_trans',
                            name: 'kode_trans'
                        },
                        {
                            data: 'tgl_bayar',
                            name: 'tgl_bayar'
                        },
                        {
                            data: 'status_bayar',
                            name: 'status_bayar'
                        },
                        {
                            data: 'verifikasi',
                            name: 'verifikasi'
                        },
                        {
                            data: 'metode_bayar',
                            name: 'metode_bayar'
                        },
                        {
                            data: 'nama_bank',
                            name: 'nama_bank'
                        },
                        {
                            data: 'nama_rek',
                            name: 'nama_rek'
                        },
                        {
                            data: 'no_rek',
                            name: 'no_rek'
                        },
                        {
                            data: 'jml_bayar',
                            name: 'jml_bayar'
                        },
                        {
                            data: 'file',
                            name: 'file'
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
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                var verifikasi = $('#verifikasi').val();
                var metode_bayar = $('#metode_bayar').val();

                    $('#datatable').DataTable().destroy();
                    load_data(from_date, to_date, verifikasi, metode_bayar);
                    $("#print").val('Print');
                    $("#export").val('Export');

            });

            $('#refresh').click(function() {
                $('#from_date').val('');
                $('#to_date').val('');
                $('#verifikasi').val('');
                $('#metode_bayar').val('');
                $('#datatable').DataTable().destroy();
                load_data();
            });

            $('body').on('click', '#btnVerifikasi', function() {
                id = $(this).data('id_pembayaran');
                $('#verifikasiForm').trigger("reset");
                $('#verifikasiId').val(id);
                $('#modalVerifikasi').modal('show');
            });

            $(document).on('click','.aksiBtn',function(e) {
                e.preventDefault();
                aksi = $(this).data('id');
                if (aksi == 1) {
                    url = "{{ url('/pembayaran/verifikasi-pembayaran') }}" + '/' + aksi
                } else
                if (aksi == 2) {
                    url = "{{ url('/pembayaran/verifikasi-pembayaran') }}" + '/' + aksi
                }
                var form = $('#verifikasiForm')[0];
                var formData = new FormData(form);
                $.ajax({
                    data: formData,
                    url: url,
                    type: "POST",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        if (data.success == true) {
                            $('#verifikasiForm').trigger("reset");
                            $('#modalVerifikasi').modal('hide');
                            $('#datatable').DataTable().destroy();
                            load_data();
                            swal("Pesan", data.message, "success");
                        } else {
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
                            swal("Pesan", err, "error");
                        }
                    }
                });
            });



            $('.select2').select2({
                dropdownParent: $('#ajaxModel')
            });

            $('#createNewPembayaran').click(function() {
                $('#saveBtn').html("Simpan");
                $('#id_pembayaran').val('');
                $('#pembayaranForm').trigger("reset");
                $('#modelHeading').html("Tambah Pembayaran ");
                $('#ajaxModel').modal('show');
            });

            $('#saveBtn').click(function(e) {
                e.preventDefault();
                $(this).html('Menyimpan..');

                var form = $('#pembayaranForm')[0];
                var formData = new FormData(form);
                $("#canvasloading").show();
                $("#loading").show();
                $.ajax({
                    data: formData,
                    url: "{{ url('/pembayaran/simpan') }}",
                    type: "POST",
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.success == true) {

                            $('#pembayaranForm').trigger("reset");
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

            $('body').on('click', '.editPembayaran', function() {

                var id_pembayaran = $(this).data('id_pembayaran');
                $.get("{{ url('/pembayaran') }}" + '/' + id_pembayaran + '/edit', function(data) {
                    $('#modelHeading').html("Ubah Pembayaran ");
                    $('#saveBtn').html('Perbaharui');
                    $('#ajaxModel').modal('show');
                    $('#id_pembayaran').val(data.id);
                    $('#tgl_bayar').val(data.tgl_bayar);
                    $('#metode_bayar').val(data.metode_bayar);
                    $('#judul').val(data.judul);
                    $('#biaya').val(data.biaya);
                    $('#verifikasi').val(data.verifikasi);
                    $('#deskripsi').val(data.deskripsi);
                })

            });

            $('body').on('click', '.deletePembayaran', function() {

                var id_pembayaran = $(this).data("id_pembayaran");
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
                                url: "{{ url('/pembayaran') }}" + '/' + id_pembayaran,
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
                                    swal("Pesan", data.message, "error");
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
