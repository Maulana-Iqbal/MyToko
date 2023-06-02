@extends('layouts.app')

@section('content')
<style>
    .table> :not(caption)>*>* {
        padding: 8px;
    }

    .table {
        min-width: 900px;
    }
</style>
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
                <div class="col-sm-4">
                    @can('transaksi','transaksi-create')
                    <a href="/transaksi/baru" class="btn btn-primary mb-2"><i class="mdi mdi-plus-circle me-2"></i>
                        Tambah Penjualan</a>
                    @endcan
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
                    <table id="datatable" class="table table-centered w-100 dt-responsive  table-striped">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 20px;">No</th>
                                <th>Tanggal</th>
                                <th class="text-wrap">No. Nota</th>
                                <th>Customer</th>
                                <th>Perusahaan</th>
                                <th>Total</th>
                                <th class="text-wrap">Metode Bayar</th>
                                <th>Status</th>
                                <th>Toko</th>
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
<!-- end row -->



{{-- <div id="modalProses" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-top">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title" id="topModalLabel">Proses Transaksi</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    Pelanggan telah dipilih, Klik Proses Transaksi untuk menyimpan!
                    <input type="hidden" id="pelangganId" name="pelangganId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="saveBtn2" class="btn btn-primary">Proses Transaksi</button>
                </div>
            </div>
        </div>
    </div> --}}




@include('transaksi.modal.modalVerifikasi')
@include('transaksi.modal.modalPengiriman')
@include('transaksi.modal.modalPembayaran')



@include('transaksi.jsTransaksi')
@endsection