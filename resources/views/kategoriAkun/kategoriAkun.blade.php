@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">

                <h4 class="page-title">Kategori Akun</h4>
            </div>
        </div>
    </div>
    <!-- <ul class="nav nav-tabs nav-bordered mb-3">
        <li class="nav-item">
            <a href="#dataKategoriAkun" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
                {{-- <i class="mdi mdi-home-variant d-md-none d-block"></i> --}}
                <span class="d-none d-md-block">Kategori Akun</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="#dataTrash" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                {{-- <i class="mdi mdi-account-circle d-md-none d-block"></i> --}}
                <span class="d-none d-md-block">Sampah</span>
            </a>
        </li>
    </ul> -->
    <div class="tab-content">
        <div class="tab-pane show active" id="dataKategoriAkun">
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-body">
                            <!-- @if (auth()->user()->level == 'SUPERADMIN' or auth()->user()->level == 'STAFF' or auth()->user()->level == 'ADMIN')
                                <div class="mb-2">
                                    <button type="button" id="createNewKategoriAkun" class="btn btn-primary"
                                        data-bs-toggle="modal" data-bs-target="#success-header-modal"><i
                                            class="mdi mdi-plus-circle me-2"></i>
                                        Tambah</button>
                                    <button type="button" name="bulk_delete" id="bulk_delete"
                                        class="btn btn-outline-danger float-end">Hapus
                                        Pilihan</button>
                                </div>
                            @endif -->
                            <div class="table-responsive">
                                <table id="datatable" class="table table-centered w-100 dt-responsive  table-striped">
                                    <thead>
                                        <tr>
                                            <th width="20px">No</th>
                                            <!-- <th class="all" style="width: 20px;">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="master">
                                                    <label class="form-check-label" for="master">&nbsp;</label>
                                                </div>
                                            </th> -->
                                            <th>Kode</th>
                                            <th>Name</th>
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
                                <table id="datatableTrash"
                                    class="table table-centered w-100 dt-responsive  table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode</th>
                                            <th class="all">Name</th>
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

@include('kategoriAkun.modal.modalKategoriAkun')

@include('kategoriAkun.jsKategoriAkun')
@endsection
