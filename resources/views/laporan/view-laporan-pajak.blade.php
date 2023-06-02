@extends("layouts.app")

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">

            <h4 class="page-title m-0 ms-3">Laporan Pajak</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <form action="/print-laporan-pajak" target="_blank" method="get">
            @csrf
            <div class="card">
                <div class="card-body">
                    <h4>Filter</h4>
                    <div class="row">
                        <div class="col-md-9 input-daterange">
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
                                <div class="col-md-6">
                                    <label for="jenis_pajak">Jenis Pajak</label>
                                    <select name="jenis_pajak" id="jenis_pajak" class="form-control">
                                        <option value="">Semua</option>
                                        <option value="ppn">PPN</option>
                                        <option value="pph">PPH</option>
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
                <table id="datatable" class="table dt-responsive table-striped">
                    <thead>
                        <tr>
                            <th>Kode Transaksi</th>
                            <th>Perusahaan / Instansi</th>
                            <th>Tanggal Transaksi</th>
                            <th>Biaya Jasa</th>
                            <th>Total</th>
                            <th>PPN (11%)</th>
                            <th>PPH Jasa (2%)</th>
                            <th>Lampiran PPN</th>
                            <th>Lampiran PPH</th>
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
<script>
    $(document).ready(function() {
        $('.input-daterange').datepicker({
            todayBtn: 'linked',
            format: 'yyyy-mm-dd',
            autoclose: true
        });

        load_data();

        function load_data(from_date = '', to_date = '', status_trans = '',website='',jenis_pajak='') {
            $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                // retrieve: true,
                destroy: true,
                ajax: {
                    type:'post',
                    url: '{{ url("/laporan/pajak/table") }}',
                    data: {
                        from_date: from_date,
                        to_date: to_date,
                        status_trans: status_trans,
                        website:website,
                        jenis_pajak:jenis_pajak,
                    }
                },
                columns: [{
                        data: 'kode_trans',
                        name: 'kode_trans'
                    },
                    {
                        data: 'perusahaan',
                        name: 'perusahaan'
                    },
                    {
                        data: 'tgl_trans',
                        name: 'tgl_trans'
                    },
                    {
                        data: 'totalBiaya',
                        name: 'totalBiaya'
                    },
                    {
                        data: 'subtotal',
                        name: 'subtotal'
                    },
                    {
                        data: 'ppn',
                        name: 'ppn'
                    },
                    {
                        data: 'pph',
                        name: 'pph'
                    },
                    {
                        data: 'filePpn',
                        name: 'filePpn'
                    },
                    {
                        data: 'file',
                        name: 'file'
                    }
                ]
            });
        }

        $('#filter').click(function() {
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var status_trans = $('#status_trans').val();
            var website = $('#website').val();
            var jenis_pajak = $('#jenis_pajak').val();
                $('#datatable').DataTable().destroy();
                load_data(from_date, to_date, status_trans,website,jenis_pajak);
                $("#print").val('Print');
                $("#export").val('Export');
            
        });

        $('#refresh').click(function() {
            $('#from_date').val('');
            $('#to_date').val('');
            $('#status_trans').val('');
            $('#website').val('');
            $('#jenis_pajak').val('');
            $('#datatable').DataTable().destroy();
            load_data();
        });

    });
    </script>
    @endsection