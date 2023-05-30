@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Penjualan</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">

        <div class="card">
            <div class="card-body">

                <div class="col-lg-12">
                            @if(auth()->user()->can('penjualan') or auth()->user()->can('penjualan-create'))
                    <a href="{{url('penjualan/create')}}" class="btn btn-primary mb-2"><i class="mdi mdi-plus-circle me-2"></i>
                        Tambah Penjualan</a>
                    @endif
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <form action="{{url('penjualan/laporan')}}" target="_blank" id="form-filter" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-9">

                                    <div class="row input-daterange mb-2">
                                        <div class="col-md-6">
                                            <label for="from">Dari Tanggal</label>
                                            <input type="text" name="from" id="from" class="form-control" placeholder="From Date" readonly />
                                        </div>
                                        <div class="col-md-6">
                                            <label for="to">Sampai Tanggal</label>
                                            <input type="text" name="to" id="to" class="form-control" placeholder="To Date" readonly />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-2">
                                                <label for="nomor">No. Nota</label>
                                                <select multiple name="nomor[]" id="nomor" class="form-control">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="sales">Sales</label>
                                            <select multiple name="sales[]" id="sales" class="form-control">

                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="customer">Customer</label>
                                            <select multiple name="customer[]" id="customer" class="form-control">

                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="status-bayar">Status Bayar</label>
                                            <select multiple name="status_bayar[]" id="status-bayar" class="form-control">
                                                <option value="1">Lunas</option>
                                                <option value="2">Belum Lunas</option>
                                                <option value="3">Belum Bayar</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="status-order">Status Penjualan</label>
                                            <select multiple name="status_order[]" id="status-order" class="form-control">
                                                <option value="dikirim">Dikirim</option>
                                                <option value="proses">Proses</option>
                                                <option value="selesai">Selesai</option>
                                                <option value="batal">Batal</option>
                                            </select>
                                        </div>
                                        @can('show-all')

                                        <div class="col-md-4">
                                            <label for="website">Toko</label>
                                            <select multiple name="website[]" id="website" class="form-control">

                                                @foreach (dataWebsite() as $item)
                                                <option value="{{ $item->id }}">{{ $item->nama_website }}
                                                    @endforeach
                                            </select>
                                        </div>
                                        @endcan

                                    </div>

                                </div>
                                <div class="col-md-3">
                                    <label for="">Aksi</label>
                                    <div class="form-group">
                                        <a id="filter" class="btn btn-outline-primary mb-2">Filter</a>
                                        <a id="refresh" class="btn btn-outline-info mb-2">Reset</a><br>
                            @if(auth()->user()->can('penjualan') or auth()->user()->can('penjualan-laporan'))
                                        <button type="submit" name="print" value="Print" class="btn btn-outline-success mb-2"><i class="mdi mdi-printer"></i> Print</button>
                                        <button type="submit" name="export" value="Excel" class="btn btn-outline-warning mb-2"><i class="mdi mdi-file-excel-box"></i> Excel</button>
                                        <button type="submit" name="pdf" value="PDF" class="btn btn-outline-danger mb-2"><i class="mdi mdi-file-pdf-box"></i> Pdf</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>
                        <br>
                    </div>
                </div>

                <br />
                <div class="table-responsive">
                    <table id="datatable" class="table table-centered table-bordered table-hover w-100 dt-responsive nowrap dataTable no-footer dtr-inline">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 20px;">No</th>
                                <th>Tanggal</th>
                                <th>No. Nota</th>
                                <th>Sales</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Status Bayar</th>
                                <th>Status Penjualan</th>
                                <th>Aksi</th>
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


<script>
    $(document).ready(function() {
        $('#filter').click(function() {
            var from = $('#from').val();
            var to = $('#to').val();
            var nomor = $('#nomor').val();
            var sales = $('#sales').val();
            var customer = $('#customer').val();
            var status_bayar = $('#status-bayar').val();
            var status_order = $('#status-order').val();
            var website = $('#website').val();

            $('#datatable').DataTable().destroy();
            load_data(from, to, nomor, sales, customer, status_bayar, status_order, website);
            $("#print").val('Print');
            $("#export").val('Export');

        });

        $('#refresh').click(function() {
            $('#form-filter').trigger("reset");
            $("#nomor").val('').trigger('change');
            $("#sales").val('').trigger('change');
            $("#customer").val('').trigger('change');
            $("#website").val('').trigger('change');
            $("#from").val('');
            $("#to").val('');
            $("#status-bayar").val('');
            $("#status-order").val('');
            $('#datatable').DataTable().destroy();
            load_data();
        });

        load_data();

        function load_data(from = null, to = null, nomor = null, sales = null, customer = null, status_bayar = null, status_order = null, website = null) {
            var table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                retrieve: true,
                paging: true,
                destroy: true,
                "scrollX": false,
                ajax: {
                    url: "{{ url('/penjualan/table') }}",
                    type: "POST",
                    data: {
                        from_date: from,
                        to_date: to,
                        nomor: nomor,
                        sales: sales,
                        customer: customer,
                        status_bayar: status_bayar,
                        status_order: status_order,
                        website: website,
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
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
                        data: 'sales',
                        name: 'sales'
                    },
                    {
                        data: 'customer',
                        name: 'customer'
                    },
                    {
                        data: 'total',
                        name: 'total'
                    },
                    {
                        data: 'status_bayar',
                        name: 'status_bayar'
                    },
                    {
                        data: 'status_order',
                        name: 'status_order'
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


        $("#sales").select2({
            // placeholder: 'Sales Umum',
            allowClear: true,
            // dropdownParent: $('#modalStock .modal-body'),
            ajax: {
                url: "/sales/select",
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

        $("#customer").select2({
            // placeholder: 'Sales Umum',
            allowClear: true,
            // dropdownParent: $('#modalStock .modal-body'),
            ajax: {
                url: "/pelanggan/select",
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

        $("#website").select2({
            allowClear: true,
        })

        $("#status-bayar").select2({
            allowClear: true,
        })

        $("#status-order").select2({
            allowClear: true,
        })

        $("#nomor").select2({
            // placeholder: 'Pilih Kategori',
            // allowClear: true,
            // dropdownParent: $('#modalStock .modal-body'),
            ajax: {
                url: "{{url('penjualan/no/select')}}",
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

    })
</script>
@endsection