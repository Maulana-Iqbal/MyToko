@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">

            <h4 class="page-title">Barang</h4>
        </div>
    </div>
</div>
<!-- end page title -->
<ul class="nav nav-tabs nav-bordered mb-3">
    <li class="nav-item">
        <a href="#dataProduk" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
            <i class="mdi mdi-table d-md-none d-block"></i>
            <span class="d-none d-md-block">Barang</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="#dataTrash" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
            <i class="mdi mdi-delete d-md-none d-block"></i>
            <span class="d-none d-md-block">Sampah</span>
        </a>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane show active" id="dataProduk">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                            @if(auth()->user()->can('produk') or auth()->user()->can('produk-create'))
                        <button type="button" id="createNewProduk" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#success-header-modal"><i class="mdi mdi-plus-circle me-2"></i>
                            Tambah</button>
                        @endif
                        <a href="{{url('barcode')}}" class="btn btn-outline-success text-end mb-2">Buat Barcode</a>
                    </div>
                    <div class="card-body">


                        <form action="/print-laporan-produk" id="form-filter-produk" target="_blank" method="post">
                            @csrf

                            <div class="row">

                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="productFilter">Cari Barang</label>
                                            <select multiple name="produk[]" id="produkFilter" class="form-control">
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="kategoriFilter" class="control-label">Kategori</label>
                                            <select multiple id="kategoriFilter" name="kategori[]" class="form-control">
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="satuanFilter" class="control-label">Satuan</label>
                                            <select multiple id="satuanFilter" name="satuan[]" class="form-control">
                                            </select>
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
                                        <a id="refresh" class="btn btn-outline-info mb-2">Refresh</a><br>
                                        {{-- <input type="submit" name="print" value="Print" class="btn btn-success">
                                            <input type="submit" name="export" value="Excel" class="btn btn-warning">
                                            <input type="submit" name="pdf" value="PDF" class="btn btn-danger"> --}}
                                    </div>
                                </div>
                            </div>
                        </form>
                        <br />
                        <div class="table-responsive">
                            <table class="table table-centered table-bordered table-hover w-100 dt-responsive nowrap dataTable no-footer dtr-inline" id="datatable">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th style="width: 85px;">Aksi</th>
                                        <th class="text-wrap">Kode Barang</th>
                                        <th class="all">Nama Barang</th>
                                        <th>Kategori</th>
                                        <th>Stock</th>
                                        <th>Harga Modal</th>
                                        <th>Harga Jual</th>
                                        <th>Harga Grosir</th>
                                        <th>Profit</th>
                                        <th>Laba</th>
                                        <th class="text-wrap">Qr Code</th>
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
    </div>


    <div class="tab-pane" id="dataTrash">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-centered table-bordered table-hover w-100 dt-responsive nowrap dataTable no-footer dtr-inline" id="datatableTrash">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th style="width: 85px;">Aksi</th>
                                        <th class="text-wrap">Kode Barang</th>
                                        <th class="all">Nama Barang</th>
                                        <th>Kategori</th>
                                        <th>Stock</th>
                                        <th>Harga Modal</th>
                                        <th>Harga Jual</th>
                                        <th>Harga Grosir</th>
                                        <th>Profit</th>
                                        <th>Laba</th>
                                        <th class="text-wrap">Qr Code</th>
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
    </div>
</div>

@include('produk.modal.modalProduk')
@include('produk.modal.modalUploadGambar')
@include('produk.jsProduk')
@endsection