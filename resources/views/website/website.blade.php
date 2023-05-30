@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">

                <h4 class="page-title">Toko</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-body">
                @if(auth()->user()->can('toko') or auth()->user()->can('toko-create'))
                        <div class="mb-2">
                            <a href="/toko/baru" class="btn btn-outline-primary"><i class="mdi mdi-plus-circle me-2"></i>
                                Tambah</a>
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table id="datatable" class="table table-centered table-bordered table-hover w-100 dt-responsive nowrap dataTable no-footer dtr-inline">
                            <thead class="table-light">
                                <tr>
                                    <th width="20px">No</th>
                                    <th>Nama Toko</th>
                                    <th>Icon</th>
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



    <script type="text/javascript">
        $(document).ready(function() {

            var table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                retrieve: true,
                paging: true,
                destroy: true,
                "scrollX": false,
                ajax: {
                    url: "{{ url('/website/table') }}",
                    type: "POST",

                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_website',
                        name: 'nama_website'
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






            $('body').on('click', '.deleteWebsite', function() {
                var id_website = $(this).data("id_website");
                    myurl = "{{ url('/website/delete') }}" + '/' + id_website
                    msg ="Data Toko yang dihapus tidak dapat dikembalikan!";
                
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
                                        $.NotificationApp.send("Berhasil", data.message,
                                            "top-right", "",
                                            "info")
                                    } else {
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
                    });
            });

        });
    </script>
@endsection
