@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">

            <h4 class="page-title">Penawaran</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
            @if(session('alert')=='error')
                <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <strong>Error - </strong> {{session('message')}}
                </div>
                @endif
                @if(session('alert')=='success')
                <div class="alert alert-primary alert-dismissible bg-primary text-white border-0 fade show" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <strong>Success - </strong> {{session('message')}}
                </div>
                @endif
                <div class="row mb-2">
                    <div class="col-sm-4">
                        <button type="button" id="createNewQuotation" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#success-header-modal"><i class="mdi mdi-plus-circle"></i>
                            Tambah Penawaran</button>

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
                <!-- <form action="/print-laporan-quotation" target="_blank" method="post">
                    @csrf

                    <h4>Filter</h4>
                    <div class="row">
                        <div class="col align-selft-start input-daterange">
                            <div class="row">
                                <div class="col align-self-start">
                                    <label for="from_date">Dari Tanggal</label>
                                    <input type="text" name="from_date" id="from_date" class="form-control" placeholder="From Date" readonly />
                                </div>
                                <div class="col align-self-end">
                                    <label for="to_date input-daterange">Sampai Tanggal</label>
                                    <input type="text" name="to_date" id="to_date" class="form-control" placeholder="To Date" readonly />
                                </div>
                            </div>
                            <div class="col-4">
                                <label for="status_quo">Status</label>
                                <select name="status_quo" id="status_quo" class="form-control">
                                    <option value="">Semua</option>
                                    <option value="0">Draft</option>
                                    <option value="1">Diajukan</option>
                                    <option value="2">Disetujui</option>
                                    <option value="3">Ditolak / Dibatalkan</option>
                                    <option value="4">Kedaluwarsa</option>
                                </select>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <label for="">Aksi</label>
                            <div class="form-group">
                                <a id="filter" class="btn btn-outline-primary mb-2">Filter</a>
                                <a id="refresh" class="btn btn-outline-info mb-2">Refresh</a><br>
                                {{-- <input type="submit" name="print" value="Print" class="btn btn-outline-success">
                                    <input type="submit" name="export" value="Excel" class="btn btn-outline-warning">
                                    <input type="submit" name="pdf" value="PDF" class="btn btn-outline-danger"> --}}
                            </div>
                        </div>
                    </div>
                </form> -->
                <br />
                <div class="table-responsive">
                    <table class="table table-centered w-100 dt-responsive  table-striped" id="datatable">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>No Penawaran</th>
                                <th>No. Nota</th>
                                <th>Kepada</th>
                                <th>Tgl Dikeluarkan</th>
                                <th>Tgl Kedaluwarsa</th>
                                <th>Preview</th>
                                <th>Status</th>
                                <th>Toko</th>
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

<div id="ajaxModel" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="success-header-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-info">
                <h4 class="modal-title" id="success-header-modalLabel">Penawaran</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form id="quotationForm" name="quotationForm" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id_quotation" id="id_quotation">
                    <input type="hidden" name="no_quo" id="no_quo">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-2">
                                <label for="kode_trans" class="form-label">No. Nota <small>(hanya Penjualan dengan status PROSES ditampilkan)</small></label>
                                <select name="kode_trans" id="kode_trans" class="form-control">
                                    <option value="">Pilih Kode Transaksi</option>
                                    @foreach ($transaksi as $trans)
                                    <option value="{{$trans->nomor}}">{{$trans->nomor}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-2">
                                <label for="tgl_dikeluarkan" class="form-label">Tanggal Dikeluarkan</label>
                                <input class="form-control" id="tgl_dikeluarkan" type="date" name="tgl_dikeluarkan">
                            </div>
                            <div class="mb-2">
                                <label for="tgl_kedaluwarsa" class="form-label">Tanggal Kedaluwarsa</label>
                                <input class="form-control" id="tgl_kedaluwarsa" type="date" name="tgl_kedaluwarsa">
                            </div>
                            <div class="mb-2">
                                <label for="kepada" class="control-label">Kepada</label>

                                <input type="text" class="form-control" id="kepada" name="kepada" placeholder="Yth, Kepala Abcd" required="">

                            </div>
                            <div class="mb-2">
                                <label for="di" class="control-label">Di</label>

                                <input type="text" class="form-control" id="di" name="di" placeholder="Tempat" required="">

                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="mb-2">
                                        <label for="pembuka" class="control-label">Pembuka Surat <span style="color: red;">*</span></label>

                                        <textarea name="pembuka" id="pembuka" class="form-control"></textarea>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="mb-2">
                                        <label for="penutup" class="control-label">Penutup Surat <span style="color: red;">*</span></label>

                                        <textarea name="penutup" id="penutup" class="form-control"></textarea>

                                    </div>
                                </div>
                            </div>
                            <div class="mb-2">
                                <label for="catatan" class="control-label">Catatan</label>
                                <textarea name="catatan" id="catatan" class="form-control"></textarea>
                            </div>
                            <div class="mb-2">
                                <label for="status_quotation" class="form-label">Status</label>
                                <select name="status" id="status_quotation" class="form-control">
                                    <option value="">Pilih Status</option>
                                    <option value="0">Draft</option>
                                    <option value="1">Kirim</option>
                                    <option value="2">Disetujui</option>
                                    <option value="3">Ditolak</option>
                                    <option value="4">Kedaluwarsa</option>
                                </select>
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



<script src="//cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>
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

        function load_data(from_date = '', to_date = '', status_quo = '') {
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
                    url: "{{ url('/quotationTable') }}",
                    type: "POST",
                    data: {
                        from_date: from_date,
                        to_date: to_date,
                        status_quo: status_quo,
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'no_quo',
                        name: 'no_quo'
                    },
                    {
                        data: 'kode_trans',
                        name: 'kode_trans'
                    },
                    {
                        data: 'kepada',
                        name: 'kepada'
                    },
                    {
                        data: 'tgl_dikeluarkan',
                        name: 'tgl_dikeluarkan'
                    },
                    {
                        data: 'tgl_kedaluwarsa',
                        name: 'tgl_kedaluwarsa'
                    },
                    {
                        data: 'preview',
                        name: 'preview'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'toko',
                        name: 'toko'
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

        CKEDITOR.replace('pembuka');
        CKEDITOR.replace('penutup');
        CKEDITOR.replace('catatan');


        $('#filter').click(function() {
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var status_quo = $('#status_quo').val();
            $('#datatable').DataTable().destroy();
            load_data(from_date, to_date, status_quo);
            $("#print").val('Print');
            $("#export").val('Export');

        });

        $('#refresh').click(function() {
            $('#from_date').val('');
            $('#to_date').val('');
            $('#status_quo').val('');
            $('#datatable').DataTable().destroy();
            load_data();
        });

        $('body').on('click', '#btnVerifikasi', function() {
            id = $(this).data('id_quotation');
            $('#verifikasiForm').trigger("reset");
            $('#verifikasiId').val(id);
            $('#verifikasi').modal('show');
        });

        $('.aksiBtn').click(function(e) {
            e.preventDefault();
            aksi = $(this).data('id');
            if (aksi == 1) {
                url = "{{ url('/verifikasi-quotation') }}" + '/' + aksi
            } else
            if (aksi == 2) {
                url = "{{ url('/verifikasi-quotation') }}" + '/' + aksi
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

        $('#createNewQuotation').click(function() {
            $('#saveBtn').html("Simpan");
            $('#id_quotation').val('');
            $('#quotationForm').trigger("reset");
            CKEDITOR.instances['pembuka'].setData('');
            CKEDITOR.instances['penutup'].setData('');
            CKEDITOR.instances['catatan'].setData('');
            $('#modelHeading').html("Tambah Penawaran");
            $('#ajaxModel').modal('show');
        });

        $('#saveBtn').click(function(e) {
            e.preventDefault();
            $(this).html('Menyimpan..');
            for (instance in CKEDITOR.instances) {
                $('#' + instance).val(CKEDITOR.instances[instance].getData());
            }
            var form = $('#quotationForm')[0];
            var formData = new FormData(form);
            $("#canvasloading").show();
            $("#loading").show();
            var aksi = $("#saveBtn").html();
            $.ajax({
                data: formData,
                url: "{{ url('/quotation/simpan') }}",
                type: "POST",
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data.success == true) {

                        $('#quotationForm').trigger("reset");
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

        $('body').on('click', '.editQuotation', function() {

            var id_quotation = $(this).data('id_quotation');
            $.get("{{ url('/quotation') }}" + '/' + id_quotation + '/edit', function(data) {
                $('#modelHeading').html("Ubah Penawaran");
                $('#saveBtn').html('Perbaharui');
                $('#ajaxModel').modal('show');
                $('#id_quotation').val(data.id);
                $('#no_quo').val(data.no_quo);
                $('#kode_trans').val(data.kode_trans);
                $('#tgl_dikeluarkan').val(data.tgl_dikeluarkan);
                $('#tgl_kedaluwarsa').val(data.tgl_kedaluwarsa);
                $('#kepada').val(data.kepada);
                $('#di').val(data.di);
                $('#status_quotation').val(data.status);
                CKEDITOR.instances['pembuka'].setData(data.pembuka);
                CKEDITOR.instances['penutup'].setData(data.penutup);
                CKEDITOR.instances['catatan'].setData(data.catatan);
            })

        });

        $('body').on('click', '.ajukanQuotation', function() {

            var id_quotation = $(this).data("id_quotation");
            swal({
                    title: "Yakin Kirim ke Email Customer?",
                    text: "Mengirim Penawaran Kepada Customer!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ya, Kirim ke Email!",
                    cancelButtonText: "Batal!",
                    closeOnConfirm: false,
                    closeOnCancel: false
                },
                function(isConfirm) {
                    if (isConfirm) {
                        $("#canvasloading").show();
                        $("#loading").show();
                        $.ajax({
                            type: "post",
                            url: "{{ url('/quotation/kirim') }}" + '/' + id_quotation,
                            success: function(data) {
                                $('#datatable').DataTable().destroy();
                                load_data();
                                $("#canvasloading").hide();
                                $("#loading").hide();
                                swal("Deleted!", "Data Berhasil Dikirim!",
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
                        swal("Cancelled", "Kirim ke Email Customer dibatalkan...! :)", "error");
                    }
                });
        });

        $('body').on('click', '.deleteQuotation', function() {

            var id_quotation = $(this).data("id_quotation");
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
                            url: "{{ url('/quotation') }}" + '/' + id_quotation,
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