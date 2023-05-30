@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">

            <h4 class="page-title">Barang Keluar</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">

        <div class="card">
          
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action="/stock/keluar/laporan" target="_blank" method="post">
                            @csrf
                            <input type="hidden" name="jenis" value="2">
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
                                                <label for="nomorFilter">No. Nota</label>
                                                <select multiple name="nomor[]" id="nomorFilter" class="form-control">
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="productFilter">Barang</label>
                                            <select multiple name="produk[]" id="produkFilter" class="form-control">
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="satuanFilter" class="control-label">Satuan</label>
                                            <select multiple id="satuanFilter" name="satuan[]" class="form-control">
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="kategoriFilter" class="control-label">Kategori</label>
                                            <select multiple id="kategoriFilter" name="kategori[]" class="form-control">
                                            </select>
                                        </div>
                                        <!-- <div class="col-md-4">
                                            <label for="jumlahFilter">Jumlah Kecil Sama Dari</label>
                                            <input type="number" name="jumlahFilter" id="jumlahFilter" class="form-control">
                                        </div> -->
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
                                        @if(auth()->user()->can('stock') or auth()->user()->can('stock-laporan-keluar'))
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

                <table id="datatable" class="table table-centered table-bordered table-hover w-100 dt-responsive nowrap dataTable no-footer dtr-inline">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Gudang</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Satuan</th>
                            <th>Stock Awal</th>
                            <th>Jumlah</th>
                            <th>Stock Akhir</th>
                            <th>Harga Final</th>
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
                <h5 class="modal-title" id="exampleModalToggleLabel2">Tambah Stock Keluar</h5>
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
                                <label for="nota">Nomor</label>
                                <input type="text" name="nota" id="nota" class="form-control">
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


                    <button id="addRow" type="button" class="btn btn-sm btn-info mb-2">Tambah Barang</button>


                    <div id="inputFormRow">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="mb-2">
                                    <input type="hidden" name="produk_id[]" id="produk_id">
                                    <input type="hidden" name="gudang_id[]" id="gudang_id">
                                    <label for="">Barang</label>
                                    <input type="text" name="produkPilih[]" class="form-control produkPilih" readonly placeholder="Pilih Barang">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="mb-2">
                                    <label for="">Gudang</label>
                                    <input type="text" name="gudangPilih[]" class="form-control gudangPilih" readonly>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="mb-2">
                                    <label for="">Stock</label>
                                    <input type="number" readonly class="form-control" name="stock[]" id="sisaStock">
                                </div>
                            </div>
                            <div class="col-lg-2">

                                <div class="mb-2">
                                    <label for="">Jumlah</label>
                                    <input type="number" class="form-control" name="jumlah[]" min="1" id="jumlah">
                                </div>
                            </div>
                            <div class="col-lg-2">

                                <div class="mb-2 mt-3">
                                    <button id="removeRow" type="button" class="btn btn-danger float-end">X</button>
                                </div>

                            </div>
                        </div>
                    </div>


                    <div id="newRow"></div>
                    <div class="row">
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

<div id="ajaxModelProduk" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="primary-header-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-info">
                <h4 class="modal-title" id="primary-header-modalLabel">Pilih Barang</h4>
                {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button> --}}
            </div>
            <div class="modal-body" style="max-height: 400px; overflow-y: scroll;">
                <div class="table-responsive">
                    <table class="table table-centered w-100 dt-responsive  table-striped" id="datatableProdukPilih">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th class="text-wrap">Kode Barang</th>
                                <th class="all">Nama Barang</th>
                                <th>Kategori</th>
                                <th>Stock</th>
                                <th>Harga Eceran</th>
                                <th>Harga Grosir</th>
                                <th style="width: 85px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>


                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer bg-primary">
                <button type="button" class="btn btn-danger" id="tutup">Tutup</button>
                {{-- <button type="button" class="btn btn-primary" id="saveBtn">Simpan</button> --}}
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {

        $('.input-daterange').datepicker({
            todayBtn: 'linked',
            format: 'yyyy-mm-dd',
            autoclose: true
        });

        $('#filter').click(function() {
            var from_date = $('#from').val();
            var to_date = $('#to').val();
            var nomor = $('#nomorFilter').val();
            var produk = $('#produkFilter').val();
            var satuan = $('#satuanFilter').val();
            var kategori = $('#kategoriFilter').val();
            var jumlah = $('#jumlahFilter').val();
            var website = $('#website').val();

            $('#datatable').DataTable().destroy();
            load_data(nomor, from_date, to_date, produk, satuan, kategori, jumlah, website);
            $("#print").val('Print');
            $("#export").val('Export');

        });

        $('#refresh').click(function() {
            $('#form-filter-stock').trigger("reset");
            $("#nomorFilter").val('').trigger('change');
            $("#produkFilter").val('').trigger('change');
            $("#kategoriFilter").val('').trigger('change');
            $("#satuanFilter").val('').trigger('change');
            $("#from").val('');
            $("#to").val('');
            $("#website").val('').trigger('change');
            $('#datatable').DataTable().destroy();
            load_data();
        });

        function clear() {
            $("#tglTambah").val('');
            $("#produk_id").val('');
            $("#produkPilih").val('');
            $("#gudang_id").val('');
            $("#gudangPilih").val('');
            $("#sisaStock").val('');
            $("#gudangTambah").val('').trigger('change');
            $("#saveBtn").removeAttr('disabled');
            $("#saveBtn").html('Simpan');
            $("#jumlah").val(0);
            $("#deskripsi").val('');
            $("#nota").val('');
            $("#removeRow").closest('#inputFormRow').remove();
        }


        $("body").on("click", ".produkPilih", function() {
            $(this).val('selected');
            var gudang = $("#gudangTambah").val();
            if (gudang == '') {
                alert('Silahkan Pilih Gudang');
                return;
            }
            $('#datatableProdukPilih').DataTable().destroy();
            load_data_produk(gudang);
            $('#ajaxModelProduk').modal('show');
        })

        $("#addRow").click(function() {
            var html = `<div id="inputFormRow">
            <div class="row">
                            <div class="col-lg-4">
                                <div class="mb-2">
                                    <input type="hidden" name="produk_id[]" id="produk_id">
                                    <input type="hidden" name="gudang_id[]" id="gudang_id">
                                    <label for="">Barang</label>
                                    <input type="text" name="produkPilih[]" class="form-control produkPilih" readonly placeholder="Pilih Barang">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="mb-2">
                                    <label for="">Gudang</label>
                                    <input type="text" name="gudangPilih[]" class="form-control gudangPilih" readonly>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="mb-2">
                                    <label for="">Stock</label>
                                    <input type="number" readonly class="form-control" name="stock[]" id="sisaStock">
                                </div>
                            </div>
                            <div class="col-lg-2">

                                <div class="mb-2">
                                    <label for="">Jumlah</label>
                                    <input type="number" class="form-control" name="jumlah[]" min="1" id="jumlah">
                                </div>
                            </div>
                            <div class="col-lg-2">

                                <div class="mb-2 mt-3">
                                    <button id="removeRow" type="button" class="btn btn-danger float-end">X</button>
                                </div>

                            </div>
                        </div>
                        </div>`;

            $('#newRow').append(html);
        });

        // remove row
        $(document).on('click', '#removeRow', function() {
            $(this).closest('#inputFormRow').remove();
        });

        $("body").on("click", ".pilih", function() {
            var produk = document.getElementsByName('produkPilih[]');
            var produk_id = document.getElementsByName('produk_id[]');
            var stock = document.getElementsByName('stock[]');
            var gudang_id = document.getElementsByName('gudang_id[]');
            var gudang = document.getElementsByName('gudangPilih[]');

            for (var i = 0; i < produk.length; i++) {
                var a = produk[i].value;
                var b = produk_id[i].value;
                var c = gudang_id[i].value;
                if (b == $(this).data('id') && c == $(this).data('gudang')) {
                    alert('Barang Sudah Ditambahkan');
                    return;
                }
                if ($(this).data('stock') < 1) {
                    alert('Stock Kosong');
                    return;
                }
                if (a == 'selected') {
                    produk_id[i].value = $(this).data('id');
                    produk[i].value = $(this).data('kode') + '| ' + $(this).data('name');
                    stock[i].value = $(this).data('stock');
                    gudang_id[i].value = $(this).data('gudang');
                    var textGudang = $('#gudangTambah').select2('data')[0]['text'];
                    gudang[i].value = textGudang;
                }
            }
            $('#ajaxModelProduk').modal('hide');
        })

        $("body").on("click", "#tutup", function() {
            var produk = document.getElementsByName('produkPilih[]');
            var produk_id = document.getElementsByName('produk_id[]');
            for (var i = 0; i < produk.length; i++) {
                var a = produk[i].value;
                if (a == 'selected') {
                    produk_id[i].value = '';
                    produk[i].value = '';
                }
            }
            $('#ajaxModelProduk').modal('hide');
        })

        function load_data_produk(gudang = null) {
            var table = $('#datatableProdukPilih').DataTable({
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
                scrollX: false,
                ajax: {
                    url: "{{ url('/produk/pilih/table') }}",
                    type: "POST",
                    data: {
                        gudang: gudang,
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
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
                        data: 'harga_jual',
                        name: 'harga_jual'
                    },
                    {
                        data: 'harga_grosir',
                        name: 'harga_grosir'
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


        load_data();

        function load_data(nomor = null, from_date = null, to_date = null, produk = null, satuan = null, kategori = null, jumlah = null, website = null) {
            var table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                retrieve: true,
                paging: true,
                destroy: true,
                "scrollX": false,
                ajax: {
                    url: "{{ url('/stock/keluar/table') }}",
                    type: "POST",
                    data: {
                        jenis: 2,
                        nomor: nomor,
                        from_date: from_date,
                        to_date: to_date,
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
                        data: 'tgl',
                        name: 'tgl'
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
                        data: 'satuan',
                        name: 'satuan'
                    },
                    {
                        data: 'stock_awal',
                        name: 'stock_awal'
                    },
                    {
                        data: 'jumlah',
                        name: 'jumlah'
                    },
                    {
                        data: 'stock_akhir',
                        name: 'stock_akhir'
                    },
                    {
                        data: 'harga',
                        name: 'harga'
                    },
                ]
            });
            table.draw();
        }







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
                url: "{{ url('/stock/simpan-keluar') }}",
                type: "POST",
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data.success == true) {

                        $('#formUpdateStock').trigger("reset");
                        $('#modalStock').modal('hide');
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





        $("body").on("click", "#btnStock", function() {
            clear();
            $('#modalStock').modal('show');
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


        $("#nomorFilter").select2({
            // placeholder: 'Pilih Kategori',
            // allowClear: true,
            // dropdownParent: $('#modalStock .modal-body'),
            ajax: {
                url: "/stock/no/2/select",
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