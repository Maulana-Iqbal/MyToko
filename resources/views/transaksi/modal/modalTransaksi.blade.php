

    <!-- Modal -->
    <div class="modal fade" id="newTrans" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="width: 90%; max-width: 100%;">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-info">
                    <h5 class="modal-title" id="staticBackdropLabel">Transaksi Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div> <!-- end modal header -->
                <div class="card">
                    <div class="card-body">

                        <div id="progressbarwizard">

                            <ul class="nav nav-pills nav-justified form-wizard-header mb-3">
                                <li class="nav-item">
                                    <a href="#Transaksi" data-bs-toggle="tab" data-toggle="tab"
                                        class="nav-link rounded-0 pt-2 pb-2">
                                        {{-- <i class="mdi mdi-account-circle me-1"></i> --}}
                                        <span class="d-block d-sm-inline">Transaksi</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="#Pelanggan" data-bs-toggle="tab" data-toggle="tab"
                                        class="nav-link rounded-0 pt-2 pb-2">
                                        {{-- <i class="mdi mdi-checkbox-marked-circle-outline me-1"></i> --}}
                                        <span class="d-block d-sm-inline">Pelanggan</span>
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content b-0 mb-0">

                                <div id="bar" class="progress mb-3" style="height: 7px;">
                                    <div class="bar progress-bar progress-bar-striped progress-bar-animated bg-success">
                                    </div>
                                </div>

                                <div class="tab-pane" id="Transaksi">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-12">
                                                    <h4>Cari Produk</h4>
                                                    
                                                    {{-- <div class="app-search dropdown mb-2">
                                                        <form>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control"
                                                                    placeholder="Cari Produk" id="top-search">
                                                                <span class="mdi mdi-magnify search-icon"></span>

                                                            </div>
                                                        </form>
                                                        <div class="dropdown-menu dropdown-menu-animated dropdown-lg"
                                                            id="search-dropdown"
                                                            style="max-height: 300px; width: 100%; overflow: scroll;">

                                                            <div id="resultSearch" class="notification-list">


                                                            </div>
                                                        </div>
                                                    </div> --}}
                                                    <div class="row">
                                                        <div class="col-lg-12">

                                                            <h4>Daftar Order</h4>
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered" id="tabelCart">
                                                                    <thead class="table-light">
                                                                        <tr>
                                                                            <th>Kode</th>
                                                                            <th>Nama Produk</th>
                                                                            <th>Harga Modal</th>
                                                                            <th>Biaya Jasa / Satuan</th>
                                                                            <th>Harga Jual</th>
                                                                            <th>Jumlah</th>
                                                                            <th>Total</th>
                                                                            <th style="width: 50px;">Aksi</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>

                                                                    </tbody>
                                                                </table>
                                                            </div> <!-- end table-responsive-->


                                                        </div>
                                                        <!-- end col -->

                                                    </div> <!-- end row -->
                                                </div> <!-- end col -->

                                                <div class="col-lg-8">
                                                    <div class="mb-2">
                                                        <label for="tgl">Tanggal Transaksi</label>
                                                        <input class="form-control" id="tgl" type="date" name="tgl">
                                                    </div>
                                                    <div class="mb-2">
                                                        <label for="deskripsi">Catatan</label>
                                                        <textarea name="deskripsi" id="deskripsi" class="form-control"></textarea>
                                                    </div>
                                                    <div class="text-sm-start">

                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="border p-1 mt-2 mt-lg-0 rounded">
                                                        <h4 class="mb-2">Order Summary</h4>
                                                        <h5>Sub Total : <span class="subTotalCart float-end"></span>
                                                        </h5>
                                                        <h5>PPH : <input type="checkbox" name="pph" id="pph">
                                                            2%<span class="pph float-end"></span>
                                                        </h5>
                                                        <h5>PPN : <input type="checkbox" name="ppn" id="ppn">
                                                            10%<span class="ppn float-end"></span>
                                                        </h5>
                                                        <h4>Grand Total : <span class="grandTotal float-end"></span>
                                                        </h4>
                                                        <h4>Jumlah Diterima : <span class="terimaTotal float-end"></span>
                                                        </h4>
                                                    </div>




                                                </div> <!-- end col -->

                                            </div> <!-- end row-->
                                        </div> <!-- end col -->
                                    </div> <!-- end row -->
                                </div>


                                <div class="tab-pane" id="Pelanggan">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="border mt-lg-0 rounded">
                                                        <h4 class="mt-2 p-1">Daftar Pelanggan</h4>
                                                        <div class="app-search dropdown mb-2 p-2"
                                                            style="min-height: 400px;">
                                                            <form>
                                                                <div class="input-group">
                                                                    <input type="text" name="inputCari"
                                                                        class="form-control" placeholder="Cari Pelanggan"
                                                                        id="cariPelanggan">
                                                                    <span class="mdi mdi-magnify search-icon"></span>

                                                                </div>
                                                            </form>
                                                            <div id="listPelanggan" style="overflow: scroll">

                                                            </div>
                                                        </div>
                                                    </div>

                                                </div> <!-- end col -->
                                                <div class="col-lg-8">
                                                    <h4 class="mt-2">Pelanggan</h4>

                                                    <!-- <p class="text-muted mb-4">Fill the form below in order to
                                                                            send you the order's invoice.</p> -->

                                                    <form>
                                                        <input type="hidden" id="pelangganId" name="pelangganId">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="billing-first-name"
                                                                        class="form-label">Nama
                                                                        Depan <span class="text-danger">*</span></label>
                                                                    <input class="form-control" type="text"
                                                                        name="nama_depan"
                                                                        placeholder="Enter your first name"
                                                                        id="nama_depan" />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="billing-last-name"
                                                                        class="form-label">Nama
                                                                        Belakang <span
                                                                            class="text-danger">*</span></label>
                                                                    <input class="form-control" type="text"
                                                                        name="nama_belakang"
                                                                        placeholder="Enter your last name"
                                                                        id="nama_belakang" />
                                                                </div>
                                                            </div>
                                                        </div> <!-- end row -->
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="mb-3">
                                                                    <label for="perusahaan" class="form-label">Nama
                                                                        Perusahaan</label>
                                                                    <input class="form-control" type="text"
                                                                        name="perusahaan"
                                                                        placeholder="Enter your Company Name"
                                                                        id="perusahaan" />
                                                                </div>
                                                            </div>
                                                        </div> <!-- end row -->
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="billing-email-address"
                                                                        class="form-label">Alamat
                                                                        Email
                                                                        <span class="text-danger">*</span></label>
                                                                    <input class="form-control" type="email" id="email"
                                                                        name="email" placeholder="Enter your email"
                                                                        id="billing-email-address" />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="billing-phone" class="form-label">No.
                                                                        HP Aktif <span
                                                                            class="text-danger">*</span></label>
                                                                    <input class="form-control" type="text" name="telpon"
                                                                        placeholder="08xxxxxxxxxx" id="telpon" />
                                                                </div>
                                                            </div>
                                                        </div> <!-- end row -->
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <h4 class="mt-2">Alamat Pengiriman</h4>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="provinsi" class="form-label">Provinsi
                                                                        <span class="text-danger">*</span></label>
                                                                    <select name="provinsi" id="provinsi"
                                                                        class="form-control provinsi-tujuan">

                                                                        <option value="">Pilih Provinsi</option>
                                                                        @foreach ($provinces as $prov)
                                                                            <option value="{{ $prov->id }}">
                                                                                {{ $prov->name }}</option>
                                                                        @endforeach
                                                                    </select>

                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="kota" class="form-label">Kota /
                                                                        Kabupaten <span
                                                                            class="text-danger">*</span></label>
                                                                    <select name="kabupaten" id="kabupaten"
                                                                        class="form-control kota-tujuan">
                                                                        <option value="">Pilih Kota/Kabupaten
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="kecamatan" class="form-label">Kecamatan
                                                                        <span class="text-danger">*</span></label>
                                                                    <select name="kecamatan" id="kecamatan"
                                                                        class="form-control kecamatan-tujuan">

                                                                        <option value="">Pilih Kecamatan</option>

                                                                    </select>
                                                                </div>
                                                            </div>

                                                        </div> <!-- end row -->
                                                        <div class="row">

                                                            <div class="col-md-4">
                                                                <div class="mb-3">
                                                                    <label for="billing-zip-postal"
                                                                        class="form-label">Kode
                                                                        Pos <span class="text-danger">*</span></label>
                                                                    <input class="form-control" type="text"
                                                                        name="kode_pos" placeholder="Enter your zip code"
                                                                        id="kode_pos" />
                                                                </div>
                                                            </div>
                                                        </div> <!-- end row -->
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="mb-3">
                                                                    <label for="billing-address"
                                                                        class="form-label">Alamat <span
                                                                            class="text-danger">*</span></label>
                                                                    <textarea name="alamat" id="alamat" class="form-control"></textarea>
                                                                </div>
                                                            </div>
                                                        </div> <!-- end row -->


                                                        <div class="row mt-4">

                                                            <div class="col-sm-12">
                                                                <div class="d-grid">
                                                                    <button id="saveBtn" class="btn btn-primary btn-lg">
                                                                        <i class="mdi mdi-cash-multiple me-1"></i>
                                                                        Proses Sekarang
                                                                    </button>
                                                                </div>
                                                            </div> <!-- end col -->
                                                        </div> <!-- end row -->
                                                    </form>
                                                </div>


                                            </div>
                                        </div> <!-- end col -->
                                    </div> <!-- end row -->
                                </div>

                                <ul class="list-inline mb-0 wizard mt-2">
                                    <li class="previous list-inline-item">
                                        <a href="javascript:void(0);" class="btn btn-info pre">Kembali</a>
                                    </li>
                                    <li class="next list-inline-item float-end">
                                        <a href="javascript:void(0);" class="btn btn-info next">Selanjutnya</a>
                                    </li>
                                </ul>

                            </div> <!-- tab-content -->
                        </div> <!-- end #progressbarwizard-->

                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end modal content-->
        </div> <!-- end modal dialog-->
    </div> <!-- end modal-->

