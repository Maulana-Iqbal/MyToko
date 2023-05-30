@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">

            <h4 class="page-title">Semua Stock Barang</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">

        <div class="card">
            <div class="card-header">
                @if(auth()->user()->can('stock') or auth()->user()->can('stock-ubah-harga'))
                <button type="button" class="btn btn-info mb-2 ubahHarga"><i class="uil uil-dollar-sign me-2 float-left"></i>
                    Ubah Harga</button>
                @endif
             

            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        @include('stock.filterStock')
                    </div>
                </div>

                <table id="datatable" class="table table-centered table-bordered table-hover w-100 dt-responsive nowrap dataTable no-footer dtr-inline">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Stock</th>
                            <th>Satuan</th>
                            <th class="text-wrap">Harga Modal</th>
                            <th class="text-wrap">Harga Jual</th>
                            <th class="text-wrap">Harga Grosir</th>
                            <th> Estimasi Profit</th>
                            <th>Estimasi Laba</th>
                            <!-- <th>Aksi</th> -->
                        </tr>
                    </thead>

                    <tbody>

                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="modalStock" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-filled bg-info">
                <h5 class="modal-title" id="exampleModalToggleLabel2">Penambahan Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formUpdateStock">
                @csrf

                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-lg-6">
                            <div class="mb-2">
                                <label for="tglTambah">Tanggal</label>
                                <input class="form-control" id="tglTambah" type="date" name="tgl">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-2">
                                <label for="nota">No. Nota</label>
                                <input type="text" name="nota" id="nota" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-2">
                                <label for="supplierTambah" class="control-label">Supplier</label>

                                <a target="_blank" href="/supplier"><small class="text-danger float-end">Buat Supplier Baru</small></a>
                                <select id="supplierTambah" name="supplier" class="form-control select2">
                                    <option value="0">Supplier Umum</option>

                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-2">

                                <label for="gudang" class="control-label">Gudang <span style="color: red;">*</span></label>

                                <a target="_blank" href="/gudang"><small class="text-danger float-end">Buat Gudang Baru</small></a>
                                <select id="gudangTambah" name="gudang" class="form-control select2">
                                    <option value="">Pilih Gudang</option>

                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>



                    <!-- <div class="row mb-2 produkData">
                        <div class="col-6">
                            <label for="lblProdukName">Nama Barang</label>
                            <h5 class="lblProdukName" class="mb-2"></h5>
                        </div>
                        <div class="col-6">
                            <label for="lblKategori">Kategori</label>
                            <h5 class="lblKategori" class="mb-2"></h5>
                        </div>
                        <div class="col-6">
                            <label for="lblStock">Total Stock Disemua Gudang</label>
                            <h5 class="lblStock" class="mb-2"></h5>
                        </div>
                    </div> -->



                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-2">
                                <label for="productSearchTambah">Cari Barang</label>
                                <a target="_blank" href="/produk"><small class="text-danger float-end">Buat Barang Baru</small></a>
                                <select name="produk_id" id="productSearchTambah" class="form-control productSearchTambah select2">
                                    <option value="">Cari Barang</option>
                                </select>


                            </div>
                        </div>
                        <div class="col-lg-6">

                            <div class="mb-2">
                                <label for="jumlah">Jumlah Penambahan Stock <span style="color: red;">*</span></label>
                                <input type="number" class="form-control" name="jumlah" min="1" id="jumlahTambah">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="mb-2">
                                <label for="deskripsi">Keterangan</label>
                                <textarea name="deskripsi" id="deskripsi" required class="form-control"></textarea>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <a href="javascript:void(0);" class="btn btn-danger" data-bs-target="#modalStock" data-bs-toggle="modal" data-bs-dismiss="modal">Kembali</a>
                    <button id="saveBtn" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="modalTransfer" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true" aria-labelledby="exampleTransfer" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-filled bg-info">
                <h5 class="modal-title" id="exampleTransfer">Transfer Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formTransferStock">
                @csrf

                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-6">
                            <label for="tglTransfer">Tanggal</label>
                            <input class="form-control" id="tglTransfer" type="date" name="tgl">
                        </div>
                        <div class="col-6">
                            <div class="mb-2">
                                <label for="nota">No</label>
                                <input type="text" name="nota" id="nota" class="form-control">
                            </div>
                        </div>
                    </div>



                    <div class="row mb-2 produkData">
                        <div class="col-6">
                            <label for="lblProdukName">Nama Barang</label>
                            <h5 class="lblProdukName" class="mb-2"></h5>
                        </div>
                        <div class="col-6">
                            <label for="lblKategori">Kategori</label>
                            <h5 class="lblKategori" class="mb-2"></h5>
                        </div>
                        <div class="col-6">
                            <label for="lblStock">Total Stock Disemua Gudang</label>
                            <h5 class="lblStock" class="mb-2"></h5>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-6">
                            <div class="mb-2">
                                <label for="gudangTransfer1" class="control-label">Dari Gudang <span style="color: red;">*</span></label>

                                <a target="_blank" href="/gudang"><small class="text-danger float-end">Buat Gudang Baru</small></a>
                                <select id="gudangTransfer1" name="dari_gudang_id" class="form-control select2">
                                    <option value="">Pilih Gudang</option>

                                    </optgroup>
                                </select>

                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-2">
                                <label for="gudangTransfer2" class="control-label">Ke Gudang <span style="color: red;">*</span></label>

                                <a target="_blank" href="/gudang"><small class="text-danger float-end">Buat Gudang Baru</small></a>
                                <select id="gudangTransfer2" name="ke_gudang_id" class="form-control select2">
                                    <option value="">Pilih Gudang</option>

                                    </optgroup>
                                </select>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="mb-2">
                                <label for="productSearchTambah">Cari Barang</label>
                                <a target="_blank" href="/produk"><small class="text-danger float-end">Buat Barang Baru</small></a>
                                <select name="produk_id" id="productSearchTransfer" class="form-control productSearchTransfer select2">
                                    <option value="">Cari Barang</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-6">

                            <div class="mb-2">
                                <label for="jumlah">Jumlah Transfer <span style="color: red;">*</span></label>
                                <input type="number" id="jumlahTransfer" class="form-control" name="jumlah" min="1" id="jumlah">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-2">
                                <label for="deskripsi">Keterangan</label>
                                <textarea name="deskripsi" id="deskripsi" required class="form-control"></textarea>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <a href="javascript:void(0);" class="btn btn-danger" data-bs-target="#modalTransfer" data-bs-toggle="modal" data-bs-dismiss="modal">Kembali</a>
                    <button id="saveBtnTransfer" class="btn btn-success">Transfer</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->







<div class="modal fade" id="modalHapusStock" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-filled bg-info">
                <h5 class="modal-title" id="exampleModalToggleLabel2">Pengurangan Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formHapusStock">
                @csrf
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-4">
                            <label for="tglHapus">Tanggal</label>
                            <input class="form-control" id="tglHapus" type="date" name="tgl">
                        </div>
                        <div class="col-8">
                            <div class="mb-2">
                                <label for="gudangHapus" class="control-label">Gudang <span style="color: red;">*</span></label>

                                <a target="_blank" href="/gudang"><small class="text-danger float-end">Buat Gudang Baru</small></a>
                                <select id="gudangHapus" name="gudang_id" class="form-control select2">
                                    <option value="">Pilih Gudang</option>

                                    </optgroup>
                                </select>

                            </div>


                        </div>
                    </div>
                    <div class="row mb-2 produkData">
                        <div class="col-6">
                            <label for="lblProdukName">Nama Barang</label>
                            <h5 class="lblProdukName" class="mb-2"></h5>
                        </div>
                        <div class="col-6">
                            <label for="lblKategori">Kategori</label>
                            <h5 class="lblKategori" class="mb-2"></h5>
                        </div>
                        <div class="col-6">
                            <label for="lblStock">Total Stock Disemua Gudang</label>
                            <h5 class="lblStock" class="mb-2"></h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-7">
                            <div class="mb-2">
                                <label for="productSearchHapus">Cari Barang</label>
                                <a target="_blank" href="/produk"><small class="text-danger float-end">Buat Barang Baru</small></a>
                                <select name="produk_id" id="productSearchHapus" class="form-control productSearchHapus select2">
                                    <option value="">Cari Barang</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="mb-2">
                                <label for="jumlahHapus">Jumlah Pengurangan<span style="color: red;">*</span></label>
                                <input type="number" class="form-control" name="jumlahHapus" min="1" id="jumlahHapus">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-2">
                                <label for="deskripsiHapus">Keterangan <span style="color: red;">*</span></label>
                                <textarea name="deskripsiHapus" id="deskripsiHapus" required class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="javascript:void(0);" class="btn btn-danger" data-bs-target="#modalHapusStock" data-bs-toggle="modal" data-bs-dismiss="modal">Kembali</a>
                    <button id="saveHapusBtn" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>



<div class="modal fade" id="modalHargaStock" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-filled bg-info">
                <h5 class="modal-title" id="exampleModalToggleLabel2">Ubah Harga</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formHargaStock">
                @csrf
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-4">
                            <label for="tglHarga">Tanggal</label>
                            <input class="form-control" id="tglHarga" type="date" name="tgl">
                        </div>
                        <div class="col-8">

                            <label for="productSearchHarga">Cari Barang</label>
                            <a target="_blank" href="/produk"><small class="text-danger float-end">Buat Barang Baru</small></a>
                            <select name="produk_id" id="productSearchHarga" class="form-control productSearchHarga select2">
                                <option value="">Cari Barang</option>
                            </select>

                        </div>
                    </div>
                    <div class="row mb-2 produkData">
                        <div class="col-6">
                            <label for="lblProdukName">Nama Barang</label>
                            <h5 class="lblProdukName" class="mb-2"></h5>
                        </div>
                        <div class="col-6">
                            <label for="lblKategori">Kategori</label>
                            <h5 class="lblKategori" class="mb-2"></h5>
                        </div>
                        <div class="col-6">
                            <label for="lblStock">Total Stock Disemua Gudang</label>
                            <h5 class="lblStock" class="mb-2"></h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="mb-2">
                                <label for="harga_modal_baru">Harga Modal Terbaru<span style="color: red;">*</span></label>
                                <input type="text" class="form-control rupiah" name="harga_modal_baru" id="harga_modal_baru">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-2">
                                <label for="harga_jual_baru">Harga Jual Terbaru<span style="color: red;">*</span></label>
                                <input type="text" class="form-control rupiah" name="harga_jual_baru" id="harga_jual_baru">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-2">
                                <label for="harga_grosir_baru">Harga Grosir Terbaru</label>
                                <input type="text" class="form-control rupiah" name="harga_grosir_baru" id="harga_grosir_baru">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-2">
                                <label for="deskripsiHarga">Keterangan</label>
                                <textarea name="deskripsiHarga" id="deskripsiHarga" required class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="javascript:void(0);" class="btn btn-danger" data-bs-target="#modalHargaStock" data-bs-toggle="modal" data-bs-dismiss="modal">Kembali</a>
                    <button id="saveHargaBtn" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>




<!-- Modal -->
<div class="modal fade" id="modalHistoryStock" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="width: 90%; max-width: 100%;">
        <div class="modal-content">
            <div class="modal-header modal-filled bg-info">
                <h5 class="modal-title" id="exampleModalToggleLabel2">Riwayat Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="card">
                <div class="card-body" id="cardHistoryStock">
                    @include('stock.historyStock')
                </div>
            </div>
            <div class="modal-footer">
                <a href="javascript:void(0);" class="btn btn-danger" data-bs-target="#modalHistoryStock" data-bs-toggle="modal" data-bs-dismiss="modal">Keluar</a>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


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

        if ($(".rupiah").val() == '') {
            $(".rupiah").val(0);
        }

        $("body").on('focus', '.rupiah', function() {
            var rupiah = $(this).val();
            if (rupiah == 0) {
                $(this).val('');
            }
        });

        $("body").on('focusout', '.rupiah', function() {
            var rupiah = $(this).val();
            if (rupiah == '') {
                $(this).val(0);
            }
        });

        load_data();

        function load_data(produk = null, satuan = null, kategori = null, jumlah = null, website = null) {
            var table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                retrieve: true,
                paging: true,
                destroy: true,

                lengthMenu: [
                    [20, 50, 100, -1],
                    [20, 50, 100, "All"]
                ],
                "scrollX": false,
                ajax: {
                    url: "{{ url('/stockTable') }}",
                    type: "POST",
                    data: {
                        produk: produk,
                        satuan: satuan,
                        kategori: kategori,
                        jumlah: jumlah,
                        website: website
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
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
                    {
                        data: 'profit',
                        name: 'profit'
                    },
                    {
                        data: 'laba',
                        name: 'laba'
                    },
                    // {
                    //     data: 'action',
                    //     name: 'action',
                    //     orderable: false,
                    //     searchable: false
                    // },
                ]
            });
            table.draw();
        }



        $('#filter').click(function() {
            var produk = $('#produkFilter').val();
            var satuan = $('#satuanFilter').val();
            var kategori = $('#kategoriFilter').val();
            var jumlah = $('#jumlahFilter').val();
            var website = $('#website').val();

            $('#datatable').DataTable().destroy();
            load_data(produk, satuan, kategori, jumlah, website);
            $("#print").val('Print');
            $("#export").val('Export');

        });


        $("#satuanFilter").select2({
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




        $('#refresh').click(function() {
            $('#form-filter-stock').trigger("reset");
            $("#produkFilter").val('').trigger('change');
            $("#kategoriFilter").val('').trigger('change');
            $("#satuanFilter").val('').trigger('change');
            $("#jumlahFilter").val('');
            $("#website").val('').trigger('change');
            $('#datatable').DataTable().destroy();
            load_data();
        });



        $('#saveBtn').click(function(e) {
            e.preventDefault();
            $("#saveBtn").html('Menyimpan..');
            $("#saveBtn").prop('disabled', 'true');
            var produk_id = $("#productSearchTambah").val();
            var gudang = $("#gudangTambah").val();
            var jumlah = $("#jumlahTambah").val();
            var tgl = $("#tglTambah").val();
            if (produk_id == '' || produk_id == 0 || gudang == '' || gudang == 0 || jumlah == '' || jumlah == 0 || tgl == '') {
                $("#saveBtn").removeAttr('disabled');
                $("#saveBtn").html('Simpan');
                alert('Data Belum Lengkap')
                return false;
            }
            var form = $('#formUpdateStock')[0];
            var formData = new FormData(form);
            $.ajax({
                data: formData,
                url: "{{ url('/stock/simpan') }}",
                type: "POST",
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data.success == true) {

                        $('#formUpdateStock').trigger("reset");
                        // $('#modalStock').modal('hide');
                        clear();
                        load_data();
                        $('#saveBtn').html('Simpan');
                        // swal("Pesan", data.message, "success");
                        $.NotificationApp.send("Berhasil", data.message, "top-right", "",
                            "success")
                    } else {
                        $("#saveBtn").removeAttr('disabled');
                        $("#saveBtn").html('Simpan');
                        swal("Pesan", data.message, "error");
                    }
                },
                error: function(xhr) {
                    var res = xhr.responseJSON;
                    if ($.isEmptyObject(res) == false) {
                        err = '';
                        $.each(res.errors, function(key, value) {
                            // err += value + ', ';
                            err = value;
                        });
                        $("#saveBtn").removeAttr('disabled');
                        $("#saveBtn").html('Simpan');
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        swal("Pesan", err, "error");
                    }
                }
            });
        });

        $('#saveBtnTransfer').click(function(e) {
            e.preventDefault();
            $(this).html('Transfer..');
            $("#saveBtnTransfer").prop('disabled', 'true');
            var produk_id = $("#productSearchTransfer").val();
            var gudang1 = $("#gudangTransfer1").val();
            var gudang2 = $("#gudangTransfer2").val();
            var jumlah = $("#jumlahTransfer").val();
            var tgl = $("#tglTransfer").val();
            if (gudang1 == gudang2) {
                $("#saveBtnTransfer").removeAttr('disabled');
                $("#saveBtnTransfer").html('Transfer');
                alert('Tidak Bisa Transfer Ke Gudang Yang Sama')
                return false;
            }
            if (produk_id == '' || produk_id == 0 || gudang1 == '' || gudang1 == 0 || gudang2 == '' || gudang2 == 0 || jumlah == '' || jumlah == 0 || tgl == '') {
                $("#saveBtnTransfer").removeAttr('disabled');
                $("#saveBtnTransfer").html('Transfer');
                alert('Data Belum Lengkap')
                return false;
            }
            var form = $('#formTransferStock')[0];
            var formData = new FormData(form);
            $.ajax({
                data: formData,
                url: "{{ url('/stock/transfer') }}",
                type: "POST",
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data.success == true) {

                        $('#formTransferStock').trigger("reset");
                        // $('#modalStock').modal('hide');
                        clear();
                        load_data();
                        // swal("Pesan", data.message, "success");
                        $.NotificationApp.send("Berhasil", data.message, "top-right", "",
                            "success")
                    } else {
                        $("#saveBtnTransfer").removeAttr('disabled');
                        $("#saveBtnTransfer").html('Transfer');
                        swal("Pesan", data.message, "error");
                    }
                },
                error: function(xhr) {
                    var res = xhr.responseJSON;
                    if ($.isEmptyObject(res) == false) {
                        err = '';
                        $.each(res.errors, function(key, value) {
                            // err += value + ', ';
                            err = value;
                        });
                        $("#saveBtnTransfer").removeAttr('disabled');
                        $("#saveBtnTransfer").html('Transfer');
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        swal("Pesan", err, "error");
                    }
                }
            });
        });


        $('#saveHapusBtn').click(function(e) {
            e.preventDefault();
            $("#saveHapusBtn").html("Proses..");
            $("#saveHapusBtn").prop('disabled', 'true');
            var id = $("#productSearchHapus").val();
            var jumlah = $("#jumlahHapus").val();
            var deskripsi = $("#deskripsiHapus").val();
            var tgl = $("#tglHapus").val();
            if (id == '' || id == 0 || jumlah == '' || jumlah == 0 || deskripsi == '' || tgl == '') {
                $("#saveHapusBtn").removeAttr('disabled');
                $("#saveHapusBtn").html('Simpan');
                alert('Data Belum Lengkap');
                return false;
            }
            $(this).html('Menyimpan..');
            var saveUrl = "{{ url('/stock/pengurangan') }}";
            var form = $('#formHapusStock')[0];
            var formData = new FormData(form);
            $.ajax({
                data: formData,
                url: saveUrl,
                type: "POST",
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data.success == true) {

                        $('#formHapusStock').trigger("reset");
                        clear();
                        // $('#modalHapusStock').modal('hide');
                        load_data();
                        $.NotificationApp.send("Berhasil", data.message, "top-right", "",
                            "success")
                    } else {
                        $("#saveHapusBtn").removeAttr('disabled');
                        $("#saveHapusBtn").html('Simpan');
                        $.NotificationApp.send("Gagal", data.message, "top-right", "",
                            "warning")
                    }
                },
                error: function(xhr) {
                    var res = xhr.responseJSON;
                    if ($.isEmptyObject(res) == false) {
                        err = '';
                        $.each(res.errors, function(key, value) {
                            // err += value + ', ';
                            err = value;
                        });
                        $("#saveHapusBtn").removeAttr('disabled');
                        $("#saveHapusBtn").html('Simpan');
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        $.NotificationApp.send("Gagal", err, "top-right", "",
                            "warning")
                    }
                }
            });
        });



        $('#saveHargaBtn').click(function(e) {
            e.preventDefault();
            $("#saveHargaBtn").html('Menyimpan..');
            $("#saveHargaBtn").prop('disabled', 'true');
            var produk_id = $("#productSearchHarga").val();
            var tgl = $("#tglHarga").val();
            if (produk_id == '' || produk_id == 0 || tgl == '') {
                $("#saveHargaBtn").removeAttr('disabled');
                $("#saveHargaBtn").html('Simpan');
                alert('Data Belum Lengkap')
                return false;
            }
            var saveUrl = "{{ url('/stock/ubah-harga') }}";
            var form = $('#formHargaStock')[0];
            var formData = new FormData(form);
            $.ajax({
                data: formData,
                url: saveUrl,
                type: "POST",
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data.success == true) {

                        $('#formHargaStock').trigger("reset");
                        clear();
                        // $('#modalHargaStock').modal('hide');
                        load_data();
                        $.NotificationApp.send("Berhasil", data.message, "top-right", "",
                            "success")
                    } else {
                        $("#saveHargaBtn").removeProp('disabled');
                        $("#saveHargaBtn").html('Simpan');
                        $.NotificationApp.send("Gagal", data.message, "top-right", "",
                            "warning")
                    }
                },
                error: function(xhr) {
                    var res = xhr.responseJSON;
                    if ($.isEmptyObject(res) == false) {
                        err = '';
                        $.each(res.errors, function(key, value) {
                            // err += value + ', ';
                            err = value;
                        });
                        $("#saveHargaBtn").removeAttr('disabled');
                        $("#saveHargaBtn").html('Simpan');
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        $.NotificationApp.send("Gagal", err, "top-right", "",
                            "warning")
                    }
                }
            });
        });





        function clear() {
            $(".produkData").hide();
            $("#tglTambah").val('');
            $("#tglHapus").val('');
            $("#tglHarga").val('');
            $("#tglTransfer").val('');
            $("#saveBtn").removeAttr('disabled');
            $("#saveBtn").html('Simpan');
            $("#saveBtnTransfer").removeAttr('disabled');
            $("#saveBtnTransfer").html('Transfer');
            $("#saveHapusBtn").removeAttr('disabled');
            $("#saveHapusBtn").html('Simpan');
            $("#saveHargaBtn").removeAttr('disabled');
            $("#saveHargaBtn").html('Simpan');
            $("#harga").val(0);
            $("#harga_jual").val(0);
            $("#harga_grosir").val(0);
            $("#harga_modal_baru").val(0);
            $("#harga_jual_baru").val(0);
            $("#harga_grosir_baru").val(0);
            $("#jumlah").val(0);
            $("#deskripsi").val('');
            $("#nota").val('');
            $(".edt2").css("display", 'block');
            $(".lblProdukName").html('');
            $(".lblKategori").html('');
            $(".lblStock").html('');
            // $("#gudangTambah").select2("val", '0');
            $("#gudangHapus").select2("val", '0');
            $("#gudangTransfer1").select2("val", '0');
            $("#gudangTransfer2").select2("val", '0');
            // $("#productSearchTambah").select2("val", '0');
            // $("#productSearchHarga").select2("val", '0');
            // $("#productSearchHapus").select2("val", '0');
            $("#productSearchTambah").select2("trigger", "select", {
                data: {
                    id: 0,
                    text: 'Cari Barang'
                }
            });

            $("#productSearchTransfer").select2("trigger", "select", {
                data: {
                    id: 0,
                    text: 'Cari Barang'
                }
            });

            $("#productSearchHarga").select2("trigger", "select", {
                data: {
                    id: 0,
                    text: 'Cari Barang'
                }
            });

            $("#productSearchHapus").select2("trigger", "select", {
                data: {
                    id: 0,
                    text: 'Cari Barang'
                }
            });


        }

        $("body").on("click", "#btnStock", function() {
            clear();
            $('#modalStock').modal('show');
        })

        $("body").on("click", ".transferStock", function() {
            clear();
            $('#modalTransfer').modal('show');
        })


        $("body").on("click", ".deleteStock", function() {
            clear()
            $('#modalHapusStock').modal('show');
        })

        $("body").on("click", ".ubahHarga", function() {
            // stockId = $(this).data('id_stock');
            // if (stockId != '') {
            //     $.get("{{ url('/stock') }}" + '/' + stockId + '/edit', function(data) {
            //         $("#stockIdHarga").val(stockId);
            //         $("#harga_modal_baru").val(data.harga);
            //         $("#harga_jual_baru").val(data.harga_jual);
            //         $("#harga_grosir_baru").val(data.harga_grosir);
            clear();
            $('#modalHargaStock').modal('show');
            //     })

            // } else {
            //     $.NotificationApp.send("Gagal", "Stock Tidak Ditemukan", "top-right", "",
            //         "warning")
            // }
        })





        $("#productSearchTambah").select2({
            placeholder: 'Cari Barang',
            allowClear: true,
            dropdownParent: $('#modalStock .modal-body'),
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

        $("#productSearchTransfer").select2({
            placeholder: 'Cari Barang',
            allowClear: true,
            dropdownParent: $('#modalTransfer .modal-body'),
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

        $("#productSearchHarga").select2({
            placeholder: 'Cari Barang',
            allowClear: true,
            dropdownParent: $('#modalHargaStock .modal-body'),
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

        $("#productSearchHapus").select2({
            placeholder: 'Cari Barang',
            allowClear: true,
            dropdownParent: $('#modalHapusStock .modal-body'),
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





        // $("body").on("change", "#productSearchTambah", function() {
        //     produk_id = $(this).val();
        //     if (produk_id == '' || produk_id == 0) {

        //         return false;
        //     }

        //     $("#canvasloading").show();
        //     $("#loading").show();
        //     $.get("{{ url('/produk/id') }}" + '/' + produk_id, function(data) {
        //         if (data.data.id != '') {
        //             $(".lblKategori").html(data.data.kategori.nama_kategori);
        //             $(".lblProdukName").html(data.data.nama_produk);
        //             $(".lblStock").html(data.data.stock.jumlah);
        //             $(".produkData").show();
        //             $("#canvasloading").hide();
        //             $("#loading").hide();
        //             // setTimeout(function() {
        //             //     $("#satuan").select2('open');
        //             //     $('.select2-search__field').focus();
        //             // }, 1);
        //             $.NotificationApp.send("Berhasil", "Barang Dipilih", "top-right", "",
        //                 "info")
        //         } else {
        //             $.NotificationApp.send("Gagal", "Barang Tidak Ditemukan", "top-right", "",
        //                 "warning")
        //         }
        //     })
        // })

        $("body").on("change", "#productSearchTransfer", function() {
            produk_id = $(this).val();
            if (produk_id == '' || produk_id == 0) {

                return false;
            }

            $("#canvasloading").show();
            $("#loading").show();
            $.get("{{ url('/produk/id') }}" + '/' + produk_id, function(data) {
                if (data.data.id != '') {
                    $(".lblKategori").html(data.data.kategori.nama_kategori);
                    $(".lblProdukName").html(data.data.nama_produk);
                    $(".lblStock").html(data.data.stock.jumlah);
                    $(".produkData").show();
                    $("#canvasloading").hide();
                    $("#loading").hide();
                    // setTimeout(function() {
                    //     $("#satuan").select2('open');
                    //     $('.select2-search__field').focus();
                    // }, 1);
                    $.NotificationApp.send("Berhasil", "Barang Dipilih", "top-right", "",
                        "info")
                } else {
                    $.NotificationApp.send("Gagal", "Barang Tidak Ditemukan", "top-right", "",
                        "warning")
                }
            })
        })

        $("body").on("change", "#productSearchHarga", function() {
            produk_id = $(this).val();
            if (produk_id == '' || produk_id == 0) {

                return false;
            }
            $("#canvasloading").show();
            $("#loading").show();
            $.get("{{ url('/produk/id') }}" + '/' + produk_id, function(data) {
                if (data.data.id != '') {
                    $(".lblKategori").html(data.data.kategori.nama_kategori);
                    $(".lblProdukName").html(data.data.nama_produk);
                    $(".lblStock").html(data.data.stock.jumlah);
                    $("#harga_modal_baru").val(data.data.stock.harga);
                    $("#harga_jual_baru").val(data.data.stock.harga_jual);
                    $("#harga_grosir_baru").val(data.data.stock.harga_grosir);
                    $(".produkData").show();
                    $("#canvasloading").hide();
                    $("#loading").hide();
                    // setTimeout(function() {
                    //     $("#satuan").select2('open');
                    //     $('.select2-search__field').focus();
                    // }, 1);
                    $.NotificationApp.send("Berhasil", "Barang Dipilih", "top-right", "",
                        "info")
                } else {
                    $.NotificationApp.send("Gagal", "Barang Tidak Ditemukan", "top-right", "",
                        "warning")
                }
            })
        })

        $("body").on("change", "#productSearchHapus", function() {
            produk_id = $(this).val();
            if (produk_id == '' || produk_id == 0) {

                return false;
            }
            $("#canvasloading").show();
            $("#loading").show();
            $.get("{{ url('/produk/id') }}" + '/' + produk_id, function(data) {
                if (data.data.id != '') {
                    $(".lblKategori").html(data.data.kategori.nama_kategori);
                    $(".lblProdukName").html(data.data.nama_produk);
                    $(".lblStock").html(data.data.stock.jumlah);
                    $(".produkData").show();
                    $("#canvasloading").hide();
                    $("#loading").hide();
                    // setTimeout(function() {
                    //     $("#satuan").select2('open');
                    //     $('.select2-search__field').focus();
                    // }, 1);
                    $.NotificationApp.send("Berhasil", "Barang Dipilih", "top-right", "",
                        "info")
                } else {
                    $.NotificationApp.send("Gagal", "Barang Tidak Ditemukan", "top-right", "",
                        "warning")
                }
            })
        })


        $("#gudangTambah").select2({
            placeholder: 'Pilih Gudang',
            allowClear: true,
            dropdownParent: $('#modalStock .modal-body'),
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

        $("#gudangTransfer1").select2({
            placeholder: 'Pilih Gudang',
            allowClear: true,
            dropdownParent: $('#modalTransfer .modal-body'),
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

        $("#gudangTransfer2").select2({
            placeholder: 'Pilih Gudang',
            allowClear: true,
            dropdownParent: $('#modalTransfer .modal-body'),
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



        $("#gudangHapus").select2({
            placeholder: 'Pilih Gudang',
            allowClear: true,
            dropdownParent: $('#modalHapusStock .modal-body'),
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



        $("#produkFilter").select2({
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

        $("#satuanFilter").select2({
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

        $("#kategoriFilter").select2({
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

        $("#supplierTambah").select2({
            placeholder: 'Supplier Umum',
            allowClear: true,
            dropdownParent: $('#modalStock .modal-body'),
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

        $("#website").select2({});



    });
</script>
@endsection