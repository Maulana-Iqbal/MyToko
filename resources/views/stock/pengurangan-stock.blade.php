@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">

            <h4 class="page-title">Pengurangan Barang</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">

        <div class="card">
           
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action="/stock/pengurangan-stock/laporan" target="_blank" method="post">
                            @csrf
                            <input type="hidden" name="jenis" value="3">
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
                                                <label for="nomorFilter">Nomor</label>
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
                                        <div class="col-md-4">
                                            <label for="kondisiFilter" class="control-label">Kondisi</label>
                                            <select multiple id="kondisiFilter" name="kondisi[]" class="form-control">
                                                <option value="rusak">Rusak</option>
                                                <option value="hilang">Hilang</option>
                                                <option value="kedaluwarsa">Kedaluwarsa</option>
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
                                        @if(auth()->user()->can('stock') or auth()->user()->can('stock-laporan-pengurangan'))
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
                            <th>Stock Awal</th>
                            <th>Jumlah Pengurangan</th>
                            <th>Satuan</th>
                            <th>Kondisi</th>
                        </tr>
                    </thead>

                    <tbody>

                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalHapusStock" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-filled bg-info">
                <h5 class="modal-title" id="exampleModalToggleLabel2">Hapus Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formHapusStock">
                @csrf
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-6">
                            <label for="tglHapus">Tanggal</label>
                            <input class="form-control" id="tglHapus" type="date" name="tgl">
                        </div>
                        <div class="col-6">

                            <div class="mb-2">
                                <label for="nota">Nomor</label>
                                <input type="text" name="nota" id="nota" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-4">
                            <div class="mb-2">
                                <label for="gudangHapus" class="control-label">Gudang <span style="color: red;">*</span></label>

                                <a target="_blank" href="/gudang"><small class="text-danger float-end">Buat Gudang Baru</small></a>
                                <select id="gudangHapus" name="gudang_id" class="form-control select2">
                                    <option value="">Pilih Gudang</option>

                                    </optgroup>
                                </select>

                            </div>

                        </div>
                        <div class="col-4">
                            <div class="mb-2">
                                <label for="productSearchHapus">Cari Barang</label>
                                <a target="_blank" href="/produk"><small class="text-danger float-end">Buat Barang Baru</small></a>
                                <select name="produk_id" id="productSearchHapus" class="form-control productSearchHapus select2">
                                    <option value="">Cari Barang</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-2">
                                <label for="jumlahHapus">Jumlah Hapus<span style="color: red;">*</span></label>
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


<script type="text/javascript">
    $(document).ready(function() {
        load_data();

        function load_data(nomor=null,from_date=null,to_date=null,produk = null, satuan = null, kategori = null, jumlah = null,kondisi=null, website = null) {
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
                    url: "{{ url('/stock/hapus/table') }}",
                    type: "POST",
                    data: {
                        jenis: 3,
                        nomor:nomor,
                        from_date:from_date,
                        to_date:to_date,
                        produk: produk,
                        satuan: satuan,
                        kategori: kategori,
                        jumlah: jumlah,
                        kondisi:kondisi,
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
                        data: 'stock_awal',
                        name: 'stock_awal'
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
                        data: 'kondisi',
                        name: 'kondisi',
                    },
                ]
            });
            table.draw();
        }



        $('#filter').click(function() {
            var nomor = $('#nomorFilter').val();
            var from_date = $('#from').val();
            var to_date = $('#to').val();
            var produk = $('#produkFilter').val();
            var satuan = $('#satuanFilter').val();
            var kategori = $('#kategoriFilter').val();
            var jumlah = $('#jumlahFilter').val();
            var kondisi = $('#kondisiFilter').val();
            var website = $('#website').val();

            $('#datatable').DataTable().destroy();
            load_data(nomor,from_date,to_date,produk, satuan, kategori, jumlah,kondisi, website);
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
            $("#nomorFilter").val('').trigger('change');
            $("#produkFilter").val('').trigger('change');
            $("#kategoriFilter").val('').trigger('change');
            $("#satuanFilter").val('').trigger('change');
            $("#kondisiFilter").val('').trigger('change');
            $("#jumlahFilter").val('');
            $("#from").val('');
            $("#to").val('');
            $("#website").val('').trigger('change');
            $('#datatable').DataTable().destroy();
            load_data();
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



        function clear() {
            $(".produkData").hide();
            $("#tglHapus").val('');
            $("#saveHapusBtn").removeAttr('disabled');
            $("#saveHapusBtn").html('Simpan');
            $("#jumlah").val(0);
            $("#deskripsi").val('');
            $("#nota").val('');
            $("#gudangHapus").select2("val", '0');
            $("#productSearchHapus").select2("trigger", "select", {
                data: {
                    id: 0,
                    text: 'Cari Barang'
                }
            });


        }



        $("body").on("click", ".deleteStock", function() {
            clear()
            $('#modalHapusStock').modal('show');
        })




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


        $("#nomorFilter").select2({
            // placeholder: 'Pilih Kategori',
            // allowClear: true,
            // dropdownParent: $('#modalStock .modal-body'),
            ajax: {
                url: "/stock/no/3/select",
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
        $("#kondisiFilter").select2({});

    });
</script>
@endsection