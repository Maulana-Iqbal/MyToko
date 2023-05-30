@extends('layouts.app')

@section('content')
    <style>
        h4 {
            font-size: 16px;
        }
    </style>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">

                <h4 class="page-title">Laporan Buku Besar</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

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
                    <div class="table-responsive">
                        <table id="datatable" class="table table-centered w-100 dt-responsive  table-striped">
                            <thead>
                                <tr>
                                    <th width="20px">No</th>
                                    <th>Tanggal</th>
                                    <th>Akun</th>
                                    <th>Nomor</th>
                                    <th>Debit</th>
                                    <th>Kredit</th>
                                    {{-- <th>Saldo</th> --}}
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                    $saldoDebit=0;
                                    $saldoKredit=0;
                                ?>
                                @foreach ($kas as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ date('d-m-Y', strtotime($item->tgl)) }}</td>
                                        <td>{{ $item->akun->name }}</td>
                                        <td>{{ $item->nomor }}</td>
                                        <td>Rp. {{ number_format($item->debit, 0, ',', '.') }}</td>
                                        <td>Rp. {{ number_format($item->kredit, 0, ',', '.') }}</td>
                                    </tr>
                                    <?php
                                            $saldoDebit=$saldoDebit+$item->debit;
                                            $saldoKredit=$saldoKredit+$item->kredit;
                                          ?>
                                @endforeach
                                <tr>
                                    <td colspan="4">Total Saldo</td>
                                    <td>Rp. {{ number_format($saldoDebit, 0, ',', '.') }}</td>
                                    <td>Rp. {{ number_format($saldoKredit, 0, ',', '.') }}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.input-daterange').datepicker({
                todayBtn: 'linked',
                format: 'yyyy-mm-dd',
                autoclose: true
            });
        })
    </script>
@endsection
