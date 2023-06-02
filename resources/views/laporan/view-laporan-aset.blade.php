<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Aset</title>
    <!-- third party css -->
    <link href="{{ asset('hy_assets/css/vendor/dataTables.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('hy_assets/css/vendor/responsive.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('hy_assets/css/vendor/buttons.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('hy_assets/css/vendor/select.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
    <!-- third party css end -->

    <link href="{{ asset('hy_assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('hy_assets/css/app-modern.min.css') }}" rel="stylesheet" type="text/css" id="light-style" />
    <link href="{{ asset('hy_assets/css/app-modern-dark.min.css') }}" rel="stylesheet" type="text/css"
        id="dark-style" />

</head>

<body>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">

                <h4 class="page-title m-0 ms-3">Laporan Aset</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <form action="/print-laporan-aset" target="_blank" method="post">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <h4>Filter</h4>
                        <div class="row">
                            <div class="col-md-6 input-daterange">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="from_date">Dari Tanggal</label>
                                        <input type="text" name="from_date" id="from_date" class="form-control"
                                            placeholder="From Date" readonly />
                                    </div>
                                    <div class="col-md-6">
                                        <label for="to_date input-daterange">Sampai Tanggal</label>
                                        <input type="text" name="to_date" id="to_date" class="form-control"
                                            placeholder="To Date" readonly />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="kondisi">Kondisi Aset</label>
                                <select name="kondisi" id="kondisi" class="form-control">
                                    <option value="">Semua</option>
                                    <option value="Baik">Baik</option>
                                    <option value="Sedang">Sedang</option>
                                    <option value="Rusak">Rusak</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="">Aksi</label>
                                <div class="form-group">
                                    <a id="filter" class="btn btn-primary mb-2">Filter</a>
                                    <a id="refresh" class="btn btn-info mb-2">Refresh</a><br>
                                    <input type="submit" name="print" value="Print" class="btn btn-success">
                                    <input type="submit" name="export" value="Excel" class="btn btn-warning">
                                    <input type="submit" name="pdf" value="PDF" class="btn btn-danger">
                                </div>
                            </div>
                        </div>
            </form>
            <br />
            <div class="tab-content">
                <div class="tab-pane show active" id="state-saving-preview">
                <table class="table table-centered w-100 dt-responsive nowrap table-striped" id="datatable">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Kode Aset</th>
                                <th>Nama Aset</th>
                                <th>Merek</th>
                                <th>Satuan</th>
                                <th>Jumlah</th>
                                <th>Kondisi</th>
                                <th>Deskripsi</th>
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

    <!-- bundle -->
    <script src="{{ asset('hy_assets/js/vendor.min.js') }}"></script>
    <script src="{{ asset('hy_assets/js/app.min.js') }}"></script>

    <!-- third party js -->
    <script src="{{ asset('hy_assets/js/vendor/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('hy_assets/js/vendor/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('hy_assets/js/vendor/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('hy_assets/js/vendor/responsive.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('hy_assets/js/vendor/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('hy_assets/js/vendor/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('hy_assets/js/vendor/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('hy_assets/js/vendor/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('hy_assets/js/vendor/buttons.print.min.js') }}"></script>
    <script src="{{ asset('hy_assets/js/vendor/dataTables.keyTable.min.js') }}"></script>
    <script src="{{ asset('hy_assets/js/vendor/dataTables.select.min.js') }}"></script>
    <!-- third party js ends -->

    <!-- demo app -->
    <script src="{{ asset('hy_assets/js/pages/demo.datatable-init.js') }}"></script>
    <!-- end demo js-->



    <script>
    $(document).ready(function() {
        $('.input-daterange').datepicker({
            todayBtn: 'linked',
            format: 'yyyy-mm-dd',
            autoclose: true
        });

        load_data();

        function load_data(from_date = '', to_date = '', kondisi = '') {
            $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                // retrieve: true,
                destroy: true,
                ajax: {
                    url: '{{ url("/laporan-aset") }}',
                    data: {
                        from_date: from_date,
                        to_date: to_date,
                        kondisi: kondisi,
                    }
                },
                columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'kode_aset',
                name: 'kode_aset'
            },
            {
                data: 'nama_aset',
                name: 'nama_aset'
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
                data: 'deskripsi',
                name: 'deskripsi'
            },
                ]
            });
        }

        $('#filter').click(function() {
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var kondisi = $('#kondisi').val();
            if (from_date != '' && to_date != '') {
                $('#order_table').DataTable().destroy();
                load_data(from_date, to_date, kondisi);
                $("#print").val('Print');
                $("#export").val('Export');
            } else {
                alert('Both Date is required');
            }
        });

        $('#refresh').click(function() {
            $('#from_date').val('');
            $('#to_date').val('');
            $('#kondisi').val('');
            $('#order_table').DataTable().destroy();
            load_data();
        });

    });
    </script>

</body>

</html>

