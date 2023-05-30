@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Purchase Order</h4>
        </div>
    </div>
</div>
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
                <div class="col-lg-12">
                            @if(auth()->user()->can('preorder') or auth()->user()->can('preorder-create'))
                    <a href="{{url('preorder/create')}}" class="btn btn-primary mb-2"><i class="mdi mdi-plus-circle me-2"></i>
                        Tambah</a>
                    @endif
                </div>

                <!-- <div class="row">
                    <div class="col-12">
                        <form action="{{url('preorder/laporan')}}" target="_blank" id="form-filter" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-sm-9 col-12">
                                   
                                    <div class="row input-daterange mb-2">
                                        <div class="col-sm-6 col-6">
                                            <label for="from">Dari Tanggal</label>
                                            <input type="text" name="from" id="from" class="form-control" placeholder="From Date" readonly />
                                        </div>
                                        <div class="col-sm-6 col-6">
                                            <label for="to">Sampai Tanggal</label>
                                            <input type="text" name="to" id="to" class="form-control" placeholder="To Date" readonly />
                                        </div>
                                    </div>
                                    <div class="row">
                                    <div class="col-sm-4 col-12">
                                            <div class="mb-2">
                                                <label for="nomor">No. Nota</label>
                                                <select multiple name="nomor[]" id="nomor" class="form-control">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 col-12">
                                            <label for="supplier">Supplier</label>
                                            <select multiple name="supplier[]" id="supplier" class="form-control">
                                           
                                            </select>
                                        </div>
                                        
                                        <div class="col-sm-4 col-12">
                                            <label for="status-order">Status Purchase Order</label>
                                            <select multiple name="status_order[]" id="status-order" class="form-control">
                                               <option value="proses">Proses</option>
                                               <option value="dikirim">Dikirim</option>
                                               <option value="selesai">Selesai</option>
                                               <option value="batal">Batal</option>
                                            </select>
                                        </div>
                                        @can('show-all')

                                        <div class="col-sm-4 col-12">
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
                                <div class="col-sm-3 col-12">
                                    <label for="">Aksi</label>
                                    <div class="form-group">
                                        <a id="filter" class="btn btn-outline-primary mb-2">Filter</a>
                                        <a id="refresh" class="btn btn-outline-info mb-2">Reset</a><br>
                            @if(auth()->user()->can('preorder') or auth()->user()->can('preorder-laporan'))
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
                </div> -->

                <br />
                <div class="table-responsive">
                    <table id="datatable" class="table table-centered table-bordered table-hover w-100 dt-responsive nowrap dataTable no-footer dtr-inline">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 20px;">No</th>
                                <th>Tanggal</th>
                                <th>No. Nota</th>
                                <th>Supplier</th>
                                <th>Total</th>
                                <!-- <th>Status Bayar</th> -->
                                <th>Preview</th>
                                <th>Status PO</th>
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
            var supplier = $('#supplier').val();
            var status_bayar = $('#status-bayar').val();
            var status_order = $('#status-order').val();
            var website = $('#website').val();

            $('#datatable').DataTable().destroy();
            load_data(from, to, nomor, supplier, status_bayar,status_order, website);
            $("#print").val('Print');
            $("#export").val('Export');

        });

        $('#refresh').click(function() {
            $('#form-filter').trigger("reset");
            $("#nomor").val('').trigger('change');
            $("#supplier").val('').trigger('change');
            $("#website").val('').trigger('change');
            $("#from").val('');
            $("#to").val('');
            $("#status-bayar").val('');
            $("#status-order").val('');
            $('#datatable').DataTable().destroy();
            load_data();
        });

        load_data();

        function load_data(from = null, to = null, nomor = null, supplier = null,status_bayar=null,status_order=null, website = null) {
            var table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                retrieve: true,
                paging: true,
                destroy: true,
                "scrollX": false,
                ajax: {
                    url: "{{ url('/preorder/table') }}",
                    type: "POST",
                    data: {
                        from_date: from,
                        to_date: to,
                        nomor:nomor,
                        supplier: supplier,
                        status_bayar:status_bayar,
                        status_order:status_order,
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
                        data: 'supplier',
                        name: 'supplier'
                    },
                    {
                        data: 'total',
                        name: 'total'
                    },
                    // {
                    //     data: 'status_bayar',
                    //     name: 'status_bayar'
                    // },
                    {
                        data: 'preview',
                        name: 'preview',
                        orderable: false,
                        searchable: false
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


        $("#supplier").select2({
            // placeholder: 'Supplier Umum',
            allowClear: true,
            // dropdownParent: $('#modalStock .modal-body'),
            ajax: {
                url: "/supplier/select",
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
                url: "{{url('preorder/no/select')}}",
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