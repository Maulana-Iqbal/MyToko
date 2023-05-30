@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">

            <h4 class="page-title">Data Stock Gudang</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">

        <div class="card">
            <div class="card-body">

                <form action="/stock-gudang/laporan-stock-gudang" id="form-filter" target="_blank" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-9">
                            <div class="row">

                                <div class="col-md-4">
                                    <label for="product">Cari Barang</label>
                                    <select multiple name="produk[]" id="produk" class="form-control select2">
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="satuan" class="control-label">Satuan</label>
                                    <select multiple id="satuan" name="satuan[]" class="form-control">
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="kategori" class="control-label">Kategori</label>
                                    <select multiple id="kategori" name="kategori[]" class="form-control">
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="gudang" class="control-label">Gudang</label>
                                    <select multiple id="gudang" name="gudang[]" class="form-control">
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="jumlahFilter">Jumlah Kecil Sama Dari</label>
                                    <input type="number" name="jumlahFilter" id="jumlahFilter" class="form-control">
                                </div>
                                @can('show-all')

                                <div class="col-md-4">
                                    <label for="website">Toko</label>
                                    <select multiple name="website[]" id="website" class="form-control">
                                        @foreach (dataWebsite() as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama_website }}
                                        </option>
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
                                @if(auth()->user()->can('stock') or auth()->user()->can('stock-laporan-stock-gudang'))
                                <button type="submit" name="print" value="Print" class="btn btn-outline-success mb-2"><i class="mdi mdi-printer"></i> Print</button>
                                <button type="submit" name="export" value="Excel" class="btn btn-outline-warning mb-2"><i class="mdi mdi-file-excel-box"></i> Excel</button>
                                <button type="submit" name="pdf" value="PDF" class="btn btn-outline-danger mb-2"><i class="mdi mdi-file-pdf-box"></i> Pdf</button>
                                @endif
                                <!-- <div class="float-end">
                            <a target="_blank" href="/laporan/print-laporan-stock?print=true" class="btn btn-outline-success mb-2"><i class="mdi mdi-printer"></i> Print</a>
                            <a target="_blank" href="/laporan/print-laporan-stock?export=true" class="btn btn-outline-warning mb-2"><i class="mdi mdi-file-excel-box"></i> Excel</a>
                            <a target="_blank" href="/laporan/print-laporan-stock?pdf=true" class="btn btn-outline-danger mb-2"><i class="mdi mdi-file-pdf-box"></i> Pdf</a>
                        </div> -->
                            </div>
                        </div>
                    </div>
                </form>
                <br>
                <table id="datatable" class="table dt-responsive  table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Gudang</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Stock</th>
                            <th>Satuan</th>
                            <th class="text-wrap">Harga Modal</th>
                            <th class="text-wrap">Harga Jual</th>
                            <th class="text-wrap">Harga Grosir</th>
                        </tr>
                    </thead>

                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
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
        // datatable
        function load_data(produk = null, gudang = null, satuan = null, kategori = null,jumlah=null, website = null) {
            var table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                retrieve: true,
                paging: true,
                destroy: true,
                "scrollX": false,
                ajax: {
                    url: "{{ url('/stock-gudang/table') }}",
                    type: "POST",
                    data: {
                        produk: produk,
                        gudang: gudang,
                        satuan: satuan,
                        kategori: kategori,
                        jumlah:jumlah,
                        website: website
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'gudang',
                        name: 'gudang'
                    },
                    {
                        data: 'kode_produk',
                        name: 'kode_produk'
                    },
                    {
                        data: 'nama_produk',
                        name: 'nama_produk'
                    },
                    {
                        data: 'nama_kategori',
                        name: 'nama_kategori'
                    },
                    {
                        data: 'jumlah',
                        name: 'jumlah'
                    },
                    {
                        data: 'satuan',
                        name: 'satuan'
                    },
                    {
                        data: 'harga',
                        name: 'harga'
                    },
                    {
                        data: 'harga_jual',
                        name: 'harga_jual'
                    },
                    {
                        data: 'harga_grosir',
                        name: 'harga_grosir'
                    },
                ]
            });
            table.draw();
        }

        $(document).on('click', "#filter", function() {
            produk = $("#produk").val();
            satuan = $("#satuan").val();
            gudang = $("#gudang").val();
            kategori = $("#kategori").val();
            jumlah = $("#jumlahFilter").val();
            website = $("#website").val();
            $('#datatable').DataTable().destroy();
            load_data(produk, gudang, satuan, kategori, jumlah,website);
        })

        $('#refresh').click(function() {
            $('#form-filter').trigger("reset");
            $("#produk").val('').trigger('change');
            $("#kategori").val('').trigger('change');
            $("#satuan").val('').trigger('change');
            $("#gudang").val('').trigger('change');
            $("#jumlahFilter").val('');
            $('#datatable').DataTable().destroy();
            load_data();
        });

        $("#produk").select2({
            // placeholder: 'Cari Barang',
            // allowClear: true,
            // dropdownParent: $('#modalStock .modal-body'),
            ajax: {
                url: "/produk/select",
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

        $("#satuan").select2({
            // placeholder: 'Pilih Satuan',
            // allowClear: true,
            // dropdownParent: $('#modalStock .modal-body'),
            ajax: {
                url: "/satuan/select",
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

        $("#kategori").select2({
            // placeholder: 'Pilih Kategori',
            // allowClear: true,
            // dropdownParent: $('#modalStock .modal-body'),
            ajax: {
                url: "/kategori/select",
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


        $("#gudang").select2({
            // placeholder: 'Pilih Kategori',
            // allowClear: true,
            // dropdownParent: $('#modalStock .modal-body'),
            ajax: {
                url: "/gudang/select",
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

        $("#website").select2({});

    });
</script>
@endsection