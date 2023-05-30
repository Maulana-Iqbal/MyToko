@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">

                <h4 class="page-title">Detail</h4>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-body">
                    <!-- <form action="/print-laporan-kas" target="_blank" method="get">
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <h4>Filter</h4>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="row input-daterange">
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

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="akun">Akun</label>
                                                <select name="akun" id="akun" class="form-control akun select2">
                                                    <option value="">Akun</option>
                                                </select>
                                            </div>
                                            @if (auth()->user()->level == 'SUPERADMIN' or auth()->user()->level == 'SUPERCEO')
                                                    <div class="col-md-6">
                                                        <label for="website">Perusahaan</label>
                                                        <select name="website" id="website" class="form-control">
                                                            <option value="">Semua</option>
                                                            @foreach (dataWebsite() as $item)
                                                                <option value="{{ $item->id }}">
                                                                    {{ $item->nama_website }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="">Aksi</label>
                                        <div class="form-group">
                                            <a id="filter" class="btn btn-outline-primary mb-2">Filter</a>
                                            <a id="refresh" class="btn btn-outline-info mb-2">Reset</a><br>
                                            <input type="submit" name="print" value="Print"
                                                class="btn btn-outline-success">
                                            <input type="submit" name="export" value="Excel"
                                                class="btn btn-outline-warning">
                                            <input type="submit" name="pdf" value="PDF"
                                                class="btn btn-outline-danger">
                                        </div>
                                    </div>
                                </div>
                    </form>
                    <br /> -->
                    <div class="tab-content">
                        <div class="tab-pane show active" id="state-saving-preview">
                            <table id="datatable" class="table dt-responsive  table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>No. Transaksi</th>
                                        <th>Akun</th>
                                        <th>Debit</th>
                                        <th>Kredit</th>
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


    <script type="text/javascript">
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

            load_data();

            function load_data(from_date = '', to_date = '',akun='', website = '') {
                $('#datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    // retrieve: true,
                    destroy: true,
                    ajax: {
                        type: 'post',
                        url: '{{ url('/kasTable') }}',
                        data: {
                            from_date: from_date,
                            to_date: to_date,
                            website: website,
                            akun:akun
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'tgl',
                            name: 'tgl'
                        },
                        {
                            data: 'nomor',
                            name: 'nomor'
                        },
                        {
                            data: 'akun',
                            name: 'akun'
                        },
                        {
                            data: 'debit',
                            name: 'debit'
                        },
                        {
                            data: 'kredit',
                            name: 'kredit'
                        },
                    ]
                });
            }

            $('#filter').click(function() {
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                var website = $("#website").val();
                var akun = $("#akun").val();
                // if (from_date != '' && to_date != '') {
                $('#datatable').DataTable().destroy();
                load_data(from_date, to_date, akun, website);
                $("#print").val('Print');
                $("#export").val('Export');
                // } else {
                //     alert('Both Date is required');
                // }
            });

            $('#refresh').click(function() {
                $('#from_date').val('');
                $('#to_date').val('');
                $("#website").val('');
                $('#datatable').DataTable().destroy();
                load_data();
            });

            $("#akun").select2({
                placeholder: {
                            id: '', // the value of the option
                            text: 'Pilih Akun'
                        },
                allowClear: true,
                // dropdownParent: $('#ajaxModel .modal-body'),
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
                            placeholder: {
                            id: '', // the value of the option
                            text: 'Pilih Akun'
                        },
                            // dropdownParent: $('#ajaxModel .modal-body'),
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

        });
    </script>
@endsection
