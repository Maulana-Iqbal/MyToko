@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">

            <h4 class="page-title">Customer</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">

        <div class="card">
            <div class="card-header">

                <div class="mb-2">
                    @if(auth()->user()->can('customer') or auth()->user()->can('customer-create'))
                    <button type="button" id="createNewPelanggan" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#success-header-modal"><i class="mdi mdi-plus-circle me-2"></i>
                        Tambah</button>
                    @endif
                    @if(auth()->user()->can('customer') or auth()->user()->can('customer-delete'))
                    <button type="button" name="bulk_delete" id="bulk_delete" class="btn btn-outline-danger float-end">Hapus
                        Pilihan</button>
                    @endif
                </div>
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
                                <th>Nama Customer</th>
                                <th>Perusahaan</th>
                                <th>No. HP</th>
                                <th>Email</th>
                                <th>Alamat</th>
                                <th>Toko</th>
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
@include('pelanggan.modal.modalPelanggan')



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
        // $.ajaxSetup({
        //     headers: {
        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //     }
        // });

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
                url: "{{ url('/pelangganTable') }}",
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
                    data: 'nama_pelanggan',
                    name: 'nama_pelanggan'
                },
                {
                    data: 'perusahaan',
                    name: 'perusahaan'
                },
                {
                    data: 'telpon',
                    name: 'telpon'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'alamat',
                    name: 'alamat'
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





        $('#master').on('click', function(e) {
            if ($(this).is(':checked', true)) {
                $(".select").prop('checked', true);
            } else {
                $(".select").prop('checked', false);
            }
        });

        // $('.select2').select2({
        //     dropdownParent: $('#newPelanggan .modal-body')
        // });




        $('#createNewPelanggan').click(function() {
            $('#saveBtn').html("Simpan");
            $('#pelangganId').val('');
            $('#pelangganForm').trigger("reset");
            $('#modelHeading').html("Tambah Customer ");
            $("#induk").select2("trigger", "select", {
                data: {
                    id: ''
                }
            });
            $('#preview-image-before-upload').attr('src', '');
            $('#newPelanggan').modal('show');
        });

        $('#saveBtn').click(function(e) {
            e.preventDefault();
            id = $('#pelangganId').val();
            $(this).html('Menyimpan..');
            var form = $('#pelangganForm')[0];
            var formData = new FormData(form);
            $("#canvasloading").show();
            $("#loading").show();
            $.ajax({
                data: formData,
                url: "{{ url('/pelanggan/simpan') }}",
                type: "POST",
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data.success == true) {

                        $('#pelangganForm').trigger("reset");
                        $('#pelangganId').val('');
                        $('#newPelanggan').modal('hide');
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

        $('body').on('click', '.editPelanggan', function() {

            var pelangganId = $(this).data('id_pelanggan');
            $("#canvasloading").show();
            $("#loading").show();
            $.get("{{ url('/pelanggan') }}" + '/' + pelangganId + '/edit', function(data) {
                $("#canvasloading").hide();
                $("#loading").hide();
                $('#modelHeading').html("Ubah Customer ");
                $('#saveBtn').html('Perbaharui');
                $('#newPelanggan').modal('show');
                $('#pelangganId').val(data.id);
                $('#nama_depan').val(data.nama_depan);
                $('#nama_belakang').val(data.nama_belakang);
                $('#perusahaan').val(data.perusahaan);
                $('#email').val(data.email);
                $('#telpon').val(data.telpon);
                $('#kode_pos').val(data.pos);
                $('#alamat').val(data.alamat);
                $("#provinsi").select2("trigger", "select", {
                    data: {
                        id: data.provinsi_id,
                        text: data.provinsi.name
                    }
                });
                $("#kabupaten").select2("trigger", "select", {
                    data: {
                        id: data.kota_id,
                        text: data.kota.name
                    }
                });
                $("#kecamatan").select2("trigger", "select", {
                    data: {
                        id: data.kecamatan_id,
                        text: data.kecamatan.name
                    }
                });
                // $('#preview-image-before-upload').attr('src', '/icon/' + data.icon);
            })

        });

        $('body').on('click', '.deletePelanggan', function() {
            var pelangganId = $(this).data("id_pelanggan");
            status = $(this).data("status");

            var myurl = '';
            // if (status == 'trash') {
            //     myurl = "{{ url('/pelanggan-trash') }}" + '/' + pelangganId
            //     msg =
            //         "Data Pelanggan yang dihapus akan dipindahkan ke Tempat Sampah!";
            // } else if (status == 'delete') {
            myurl = "{{ url('/pelanggan-delete') }}" + '/' + pelangganId
            msg =
                "Data Customer yang dihapus tidak dapat dikembalikan!";
            // }
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
                                    Swal.fire({
                                        title: 'Deleted!',
                                        text: data.message,
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    })
                                } else {
                                    table.draw();
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


        $(document).on('click', '#bulk_delete', function() {
            var id = [];
            swal({
                    title: "Yakin hapus data yang dipilih?",
                    text: "Data yang dihapus tidak dapat dikembalikan!",
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
                                url: "{{ url('/pelanggan/bulk-delete') }}",
                                method: "get",
                                data: {
                                    id: id
                                },
                                success: function(data) {
                                    if (data.success == true) {
                                        table.draw();
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