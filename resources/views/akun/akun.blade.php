@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">

                <h4 class="page-title">Akun</h4>
            </div>
        </div>
    </div>
    <ul class="nav nav-tabs nav-bordered mb-3">
        <li class="nav-item">
            <a href="#dataAkun" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
                {{-- <i class="mdi mdi-home-variant d-md-none d-block"></i> --}}
                <span class="d-none d-md-block">Akun</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="#dataTrash" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                {{-- <i class="mdi mdi-account-circle d-md-none d-block"></i> --}}
                <span class="d-none d-md-block">Sampah</span>
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane show active" id="dataAkun">
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-body">
                            @if (auth()->user()->level == 'SUPERADMIN' or
                                auth()->user()->level == 'STAFF' or
                                auth()->user()->level == 'ADMIN')
                                <div class="mb-2">
                                    <button type="button" id="createNewAkun" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#success-header-modal"><i class="mdi mdi-plus-circle me-2"></i>
                                        Tambah</button>

                                </div>
                            @endif
                            <!-- <form action="/print-laporan-akun" target="_blank" method="get">
                                @csrf

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
                            </form> -->
                            <div class="table-responsive">
                                <table id="datatable" class="table table-centered w-100 dt-responsive  table-striped">
                                    <thead>
                                        <tr>
                                            <th width="20px">No</th>

                                            <th>Tipe</th>
                                            <th>Kode Akun</th>
                                            <th>Nama Akun</th>
                                            <th>Kategori Akun</th>
                                            <th>Saldo</th>
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
        </div>


        <div class="tab-pane" id="dataTrash">
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatableTrash" class="table table-centered w-100 dt-responsive  table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tipe</th>
                                            <th>Kode Akun</th>
                                            <th class="all">Nama Akun</th>
                                            <th>Kategori Akun</th>
                                            <th>Saldo</th>
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
        </div>
    </div>
    <!-- Success Header Modal -->

    @include('akun.modal.modalAkun')

    @include('akun.jsAkun')
@endsection
