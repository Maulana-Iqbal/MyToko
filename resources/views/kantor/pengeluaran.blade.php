@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">

                <h4 class="page-title">Pengeluaran</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-4">
                            @if (auth()->user()->level == 'SUPERADMIN' or
                                auth()->user()->level == 'STAFF' or
                                auth()->user()->level == 'ADMIN')
                                <button type="button" id="createNewPengeluaran" class="btn btn-primary mb-2"
                                    data-bs-toggle="modal" data-bs-target="#success-header-modal"><i
                                        class="mdi mdi-plus-circle"></i>
                                    Tambah</button>
                            @endif
                        </div>
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
                    <!-- <form action="/print-laporan-pengeluaran" target="_blank" method="get">
                        @csrf

                        <h4>Filter</h4>
                        <div class="row">
                            <div class="col-md-9">
                                <div class="row mb-2 input-daterange">
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
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label for="persetujuan">Status Verifikasi</label>
                                        <select name="persetujuan" id="persetujuan" class="form-control">
                                            <option value="">Semua</option>
                                            <option value="1">Menunggu Verifikasi</option>
                                            <option value="2">Disetujui</option>
                                            <option value="3">Ditolak</option>
                                        </select>
                                    </div>

                                    @if (auth()->user()->level == 'SUPERADMIN' or auth()->user()->level == 'SUPERCEO')
                                        <div class="col-md-6">
                                            <label for="website">Perusahaan</label>
                                            <select name="website" id="website" class="form-control">
                                                <option value="">Semua</option>
                                                @foreach (dataWebsite() as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nama_website }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
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
                    <div class="row mt-4">
                        <div class="col-md-12">
                            @if (auth()->user()->level == 'SUPERADMIN' or
                                auth()->user()->level == 'SUPERCEO' or
                                auth()->user()->level == 'CEO')
                                <div class="mb-2">
                                    <button type="button" name="bulk_verifikasi" id="bulk_verifikasi"
                                        class="btn btn-outline-info float-start">Verifikasi Pilihan</button>
                                </div>
                            @endif

                        </div>
                    </div>
                    <br /> -->
                    <div class="table-responsive">
                        <table class="table table-centered w-100 dt-responsive  table-striped" id="datatable">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th class="all" style="width: 20px;">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="master">
                                            <label class="form-check-label" for="master">&nbsp;</label>
                                        </div>
                                    </th>
                                    <th style="width: 85px;">Aksi</th>
                                    <th>Dari</th>
                                    <th>Akun</th>
                                    <th>Tanggal</th>
                                    <th>Jumlah</th>
                                    <th>Lampiran</th>
                                    <th>Status</th>
                                    <th>Deskripsi</th>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-info">
                    <h4 class="modal-title" id="success-header-modalLabel">Pengeluaran</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form id="pengeluaranForm" name="pengeluaranForm" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id_pengeluaran" id="id_pengeluaran">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-2">
                                    <label for="tgl" class="form-label">Tanggal Pengeluaran</label>
                                    <input class="form-control" id="tgl" type="date" name="tgl">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-2">
                                    <label for="nomor" class="form-label">No. Transaksi</label>
                                    <input class="form-control" id="nomor" type="text" name="nomor">
                                </div>





                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="dari" class="control-label">Dari</label>
                                <select name="dari" id="dari" class="form-control">
                                    <option value="">Pilih Akun</option>
                                    @foreach ($kasBank as $item)
                                        <option value="{{$item->akun_id}}">{{$item->akun->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button id="addRow" type="button" class="btn btn-sm btn-info mb-2">Tambah Akun</button>

                            <div id="listProdi">

                                <div id="inputFormRow">
                                    <div class="row">
                                        <div class="col-md-5 mb-2">
                                            <input type="hidden" name="idAkun[]" id="idAkun">
                                            <input type="text" name="akun[]" class="form-control akun" readonly
                                                placeholder="Pilih Akun">
                                        </div>
                                        <div class="col-md-5 mb-2">
                                            <input type="text" class="form-control" id="biaya" name="biaya[]"
                                    placeholder="0" required="">
                                        </div>

                                        <div class="col-md-2 mb-2">
                                            <div class="input-group-append">
                                                <button id="removeRow" type="button" class="btn btn-danger">X</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="newRow"></div>
                            {{-- <div class="col-md-4 mb-2">
                                <label for="akun">Akun</label>
                                <select name="akun" id="akun" class="form-control akun select2">
                                    <option value="">Akun</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="biaya" class="control-label">Jumlah</label>

                                <input type="text" class="form-control" id="biaya" name="biaya"
                                    placeholder="Rp. xxxxxxx" required="">

                            </div> --}}
                            <div class="col-md-6" style="display: none">
                                <div class="mb-2">
                                    <label for="file" class="control-label">Upload Berkas</label>

                                    <input type="file" class="form-control" id="file" name="file"
                                        required="">
                                    <small>Ekstensi Berkas File Diizinkan .png / .jpg / .jpeg / .pdf / .doc / .docx / .xls /
                                        .xlsx<br>Maksimal Ukuran 1MB</small>

                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-2">
                                    <label for="deskripsi" class="control-label">Deskripsi</label>

                                    <textarea name="deskripsi" id="deskripsi" class="form-control"></textarea>

                                </div>
                            </div>
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




    <div id="ajaxModelAkun" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="primary-header-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-info">
                    <h4 class="modal-title" id="primary-header-modalLabel">Pilih Akun</h4>
                    {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button> --}}
                </div>
                <div class="modal-body" style="max-height: 400px; overflow-y: scroll;">
                    <div class="table-responsive">
                    <table class="table table-centered w-100 dt-responsive" id="datatable-akun">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kategori</th>
                                <th>Kode</th>
                                <th>Nama Akun</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    </div>
                </div>
                <div class="modal-footer bg-primary">
                    <button type="button" class="btn btn-danger" id="tutup">Tutup</button>
                    {{-- <button type="button" class="btn btn-primary" id="saveBtn">Simpan</button> --}}
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="verifikasi" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-info">
                    <h5 class="modal-title" id="staticBackdropLabel">Verifikasi Pengeluaran</h5>
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


    <!-- Modal -->
    <div class="modal fade" id="bulkVerifikasi" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-info">
                    <h5 class="modal-title" id="staticBackdropLabel">Verifikasi Pengeluaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div> <!-- end modal header -->
                <form id="bulkVerifikasiForm">
                    @csrf
                    <input type="hidden" name="bulkVerifikasiId" id="bulkVerifikasiId">
                    <div class="modal-body">
                        <label for="bulkVerifikasiDeskripsi">Keterangan</label>
                        <textarea name="bulkVerifikasiDeskripsi" id="bulkVerifikasiDeskripsi" class="form-control"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keluar</button>
                        <button type="button" class="btn btn-danger bulkAksiBtn" data-id="2">Tolak</button>
                        <button type="button" class="btn btn-primary bulkAksiBtn" data-id="1">Setuju</button>
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



            $("body").on("click", ".akun", function() {
                $(this).val('selected');
                // $("#table").load('/akun/table-akun');
            load_data_akun();
                $('#ajaxModelAkun').modal('show');
            })

            $("body").on("click", ".pilih", function() {
                var akun = document.getElementsByName('akun[]');
                var idAkun = document.getElementsByName('idAkun[]');
                for (var i = 0; i < akun.length; i++) {
                    var a = akun[i].value;
                    if(a=='selected'){
                        idAkun[i].value=$(this).data('id');
                        akun[i].value=$(this).data('kode')+'| '+$(this).data('name');
                    }
                }
            $('#ajaxModelAkun').modal('hide');
            })

            $("body").on("click", "#tutup", function() {
                var akun = document.getElementsByName('akun[]');
                var idAkun = document.getElementsByName('idAkun[]');
                for (var i = 0; i < akun.length; i++) {
                    var a = akun[i].value;
                    if(a=='selected'){
                        idAkun[i].value='';
                        akun[i].value='';
                    }
                }
                $('#ajaxModelAkun').modal('hide');
            })

            $("#addRow").click(function() {
                var html = '';
                html += '<div id="inputFormRow">';
                html += '<div class="row">';
                html +=
                    '<div class="col-md-5 mb-2"><input type="hidden" name="idAkun[]" id="idAkun"><input type="text" name="akun[]" class="form-control akun" readonly placeholder="Pilih Akun"></div>';
                html +=
                    '<div class="col-md-5 mb-2"><input type="text" class="form-control" id="biaya" name="biaya[]" placeholder="0" required=""></div>';
                html += '<div class="col-md-2 mb-2"><div class="input-group-append">';
                html += '<button id="removeRow" type="button" class="btn btn-danger">X</button>';
                html += '</div>';
                html += '</div>';
                html += '</div>';

                $('#newRow').append(html);
            });

            // remove row
            $(document).on('click', '#removeRow', function() {
                $(this).closest('#inputFormRow').remove();
            });

            function load_data_akun() {
                var table = $('#datatable-akun').DataTable({
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
                        url: "{{ url('/akun/table-akun') }}",
                        type: "POST",
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'kategori',
                            name: 'kategori'
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
                            name: 'action'
                        },
                    ]
                });
                table.draw();
            }

            $('.input-daterange').datepicker({
                todayBtn: 'linked',
                format: 'yyyy-mm-dd',
                autoclose: true
            });

            load_data();

            function load_data(from_date = '', to_date = '', persetujuan = '', website = '') {
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
                        url: "{{ url('/pengeluaranTable') }}",
                        type: "POST",
                        data: {
                            from_date: from_date,
                            to_date: to_date,
                            persetujuan: persetujuan,
                            website: website,
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'select',
                            name: 'select',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'dari',
                            name: 'dari'
                        },
                        {
                            data: 'akun',
                            name: 'akun'
                        },
                        {
                            data: 'tgl',
                            name: 'tgl'
                        },
                        {
                            data: 'biaya',
                            name: 'biaya'
                        },
                        {
                            data: 'file',
                            name: 'file'
                        },
                        {
                            data: 'persetujuan',
                            name: 'persetujuan'
                        },
                        {
                            data: 'deskripsi',
                            name: 'deskripsi'
                        },
                    ]
                });
                table.draw();
            }

            $('#filter').click(function() {
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                var persetujuan = $('#persetujuan').val();
                var website = $('#website').val();

                $('#datatable').DataTable().destroy();
                load_data(from_date, to_date, persetujuan, website);
                $("#print").val('Print');
                $("#export").val('Export');

            });

            $('#refresh').click(function() {
                $('#from_date').val('');
                $('#to_date').val('');
                $('#persetujuan').val('');
                $('#website').val('');
                $('#datatable').DataTable().destroy();
                load_data();
            });

            $('#master').on('click', function(e) {
                if ($(this).is(':checked', true)) {
                    $(".select").prop('checked', true);
                } else {
                    $(".select").prop('checked', false);
                }
            });

            $('body').on('click', '#btnVerifikasi', function() {
                id = $(this).data('id_pengeluaran');
                $('#verifikasiForm').trigger("reset");
                $('#verifikasiId').val(id);
                $('#verifikasi').modal('show');
            });

            $('body').on('click', '#bulk_verifikasi', function() {
                id = $(this).data('id_pengeluaran');
                $('#bulkVerifikasiForm').trigger("reset");
                $('#bulkVerifikasiId').val(id);
                $('#bulkVerifikasi').modal('show');
            });

            $('.aksiBtn').click(function(e) {
                e.preventDefault();
                aksi = $(this).data('id');
                if (aksi == 1) {
                    url = "{{ url('/verifikasi-pengeluaran') }}" + '/' + aksi
                } else
                if (aksi == 2) {
                    url = "{{ url('/verifikasi-pengeluaran') }}" + '/' + aksi
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
                            $('#verifikasi').modal('hide');
                            $('#datatable').DataTable().destroy();
                            load_data();
                            $.NotificationApp.send("Berhasil", data.message, "top-right", "",
                                "info")
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

            // $("#akun").select2({
            //     placeholderOption: 'Pilih Akun',
            //     allowClear: true,
            //     dropdownParent: $('#ajaxModel .modal-body'),
            //     ajax: {
            //         url: "/akun/select",
            //         type: "post",
            //         dataType: 'json',
            //         delay: 250,
            //         data: function(params) {
            //             return {
            //                 //    _token: CSRF_TOKEN,
            //                 search: params.term // search term
            //             };
            //         },
            //         processResults: function(response) {
            //             return {
            //                 results: response
            //             };
            //         },
            //         cache: true
            //     }

            // });

            $('#createNewPengeluaran').click(function() {
                $('#saveBtn').html("Simpan");
                $('#id_pengeluaran').val('');
                $('#pengeluaranForm').trigger("reset");
                $('#modelHeading').html("Tambah Pengeluaran ");
                $('#ajaxModel').modal('show');
            });

            $('#saveBtn').click(function(e) {
                e.preventDefault();
                $(this).html('Menyimpan..');

                var form = $('#pengeluaranForm')[0];
                var formData = new FormData(form);
                $("#canvasloading").show();
                $("#loading").show();
                $.ajax({
                    data: formData,
                    url: "{{ url('/pengeluaran/simpan') }}",
                    type: "POST",
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.success == true) {

                            $('#pengeluaranForm').trigger("reset");

                            $('#id_pengeluaran').val('');
                            getAkun();
                            // getAkunDari();
                            // $('#ajaxModel').modal('hide');
                            $('#datatable').DataTable().destroy();
                            load_data();
                            $('#saveBtn').html('Simpan');
                            $("#canvasloading").hide();
                            $("#loading").hide();
                            $.NotificationApp.send("Berhasil", data.message, "top-right", "",
                                "success")
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

            $('body').on('click', '.editPengeluaran', function() {

                var id_pengeluaran = $(this).data('id_pengeluaran');
                $.get("{{ url('/pengeluaran') }}" + '/' + id_pengeluaran + '/edit', function(data) {
                    $('#modelHeading').html("Ubah Pengeluaran ");
                    $('#saveBtn').html('Perbaharui');
                    $('#ajaxModel').modal('show');
                    $('#id_pengeluaran').val(data.id);
                    $('#tgl').val(data.tgl);
                    $('#biaya').val(data.biaya);
                    $('#nomor').val(data.nomor);
                    $('#persetujuan').val(data.persetujuan);
                    $('#deskripsi').val(data.deskripsi);
                    $('select[name="dari"]').val(data.dari);

                    getAkun(data.akun_id);
                    // getAkunDari(data.dari);

                })

            });

            $('body').on('click', '.deletePengeluaran', function() {

                var id_pengeluaran = $(this).data("id_pengeluaran");
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
                                url: "{{ url('/pengeluaran') }}" + '/' + id_pengeluaran,
                                success: function(data) {
                                    $('#datatable').DataTable().destroy();
                                    load_data();
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

            $(document).on('click', '.bulkAksiBtn', function() {
                var id = [];
                var deskripsi = $("#bulkVerifikasiDeskripsi").val();
                aksi = $(this).data('id');
                if (aksi == 1) {
                    url = "{{ url('/pengeluaran/bulk-verifikasi') }}" + '/' + aksi
                } else
                if (aksi == 2) {
                    url = "{{ url('/pengeluaran/bulk-verifikasi') }}" + '/' + aksi
                }
                swal({
                        title: "Verifikasi Pengeluaran",
                        text: "Yakin Verifikasi Semua Data yang Dipilih?",
                        type: "info",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Ya, Verifikasi Semua!",
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
                                    url: url,
                                    method: "post",
                                    data: {
                                        verifikasiId: id,
                                        deskripsi: deskripsi
                                    },
                                    success: function(data) {
                                        if (data.success == true) {
                                            $('#bulkVerifikasiForm').trigger("reset");
                                            $('#bulkVerifikasi').modal('hide');

                                            $('#datatable').DataTable().destroy();
                                            load_data();
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
                                    text: 'Silahkan Pilih Data Yang Akan Diverifikasi...!!!',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                })
                            }
                        }
                    });
            });


            $("#akun").select2({
                placeholder: {
                            id: '', // the value of the option
                            text: 'Pilih'
                        },
                            allowClear: true,
                dropdownParent: $('#ajaxModel .modal-body'),
                ajax: {
                    url: "/akun/select",
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


            function getAkun(id = '') {
                $.ajax({
                    url: "/akun/select",
                    type: "post",
                    dataType: 'json',
                    success: function(params) {
                        $('#akun').empty();
                        $("#akun").select2({
                            dropdownParent: $('#ajaxModel .modal-body'),
                            placeholder: {
                            id: '', // the value of the option
                            text: 'Pilih'
                        },
                            allowClear: true,
                            // dropdownParent: $('#newPelanggan .modal-body'),
                            //    _token: CSRF_TOKEN,
                            data: params // search term
                        });
                        $("#akun").select2("trigger", "select", {
                            data: {
                                id: id
                            }
                        });
                    },
                });
            }


            // $("#dari").select2({
            //     placeholder: {
            //                 id: '', // the value of the option
            //                 text: 'Dari'
            //             },
            //     allowClear: true,
            //     dropdownParent: $('#ajaxModel .modal-body'),
            //     ajax: {
            //         url: "/akun/select",
            //         type: "post",
            //         dataType: 'json',
            //         delay: 250,
            //         data: function(params) {
            //             return {
            //                 //    _token: CSRF_TOKEN,
            //                 search: params.term // search term
            //             };
            //         },
            //         processResults: function(response) {
            //             return {
            //                 results: response
            //             };
            //         },
            //         cache: true
            //     }

            // });


            // function getAkunDari(id = '') {
            //     $.ajax({
            //         url: "/akun/select",
            //         type: "post",
            //         dataType: 'json',
            //         success: function(params) {
            //             $('#dari').empty();
            //             $("#dari").select2({
            //                 dropdownParent: $('#ajaxModel .modal-body'),
            //                  placeholder: {
            //                 id: '', // the value of the option
            //                 text: 'Pilih'
            //             },
            //                 allowClear: true,
            //                 // dropdownParent: $('#newPelanggan .modal-body'),
            //                 //    _token: CSRF_TOKEN,
            //                 data: params // search term
            //             });
            //             $("#dari").select2("trigger", "select", {
            //                 data: {
            //                     id: id
            //                 }
            //             });
            //         },
            //     });
            // }


        });
    </script>
@endsection
