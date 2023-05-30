@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">

            <h4 class="page-title">Transfer Barang</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">

        <div class="card">
            <div class="card-header">

                <!-- @if(auth()->user()->can('stock') or auth()->user()->can('stock-transfer')) -->
                <!-- <button type="button" class="btn btn-primary mb-2 transferStock"><i class="mdi mdi-minus-circle me-2 float-left"></i>
                    Transfer</button> -->
                <!-- @endif -->



            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action="/stock/transfer/laporan" target="_blank" method="post">
                            @csrf
                            <input type="hidden" name="jenis" value="1">
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
                                        @if(auth()->user()->can('stock') or auth()->user()->can('stock-laporan-transfer'))
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
                            <th>No. Nota</th>
                            <th>Dari Gudang</th>
                            <th>Ke Gudang</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Jumlah</th>
                            <th>Satuan</th>
                        </tr>
                    </thead>

                    <tbody>

                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>


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


        load_data();

        function load_data(from_date = null, to_date = null,nomor=null, produk = null, satuan = null, kategori = null, jumlah = null, website = null) {
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
                    url: "{{ url('/stock/transfer/table') }}",
                    type: "POST",
                    data: {
                        from_date: from_date,
                        to_date: to_date,
                        nomor:nomor,
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
                        data: 'nomor',
                        name: 'nomor'
                    },
                    {
                        data: 'dari',
                        name: 'dari'
                    },
                    {
                        data: 'ke',
                        name: 'ke'
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
                ]
            });
            table.draw();
        }



        $('#filter').click(function() {
            var from_date = $('#from').val();
            var to_date = $('#to').val();
            var nomor = $('#nomor').val();
            var produk = $('#produkFilter').val();
            var satuan = $('#satuanFilter').val();
            var kategori = $('#kategoriFilter').val();
            var jumlah = $('#jumlahFilter').val();
            var website = $('#website').val();

            $('#datatable').DataTable().destroy();
            load_data(from_date, to_date,nomor, produk, satuan, kategori, jumlah, website);
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
            $("#nomor").val('').trigger('change');
            $("#produkFilter").val('').trigger('change');
            $("#kategoriFilter").val('').trigger('change');
            $("#satuanFilter").val('').trigger('change');
            $("#jumlahFilter").val('');
            $("#from").val('');
            $("#to").val('');
            $("#website").val('').trigger('change');
            $('#datatable').DataTable().destroy();
            load_data();
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
                url: "{{ url('/stock/transfer/simpan') }}",
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




        function clear() {
            $(".produkData").hide();
            $("#tglTambah").val('');
            $("#tglHapus").val('');
            $("#tglHarga").val('');
            $("#tglTransfer").val('');
            $("#saveBtnTransfer").removeAttr('disabled');
            $("#saveBtnTransfer").html('Transfer');
            $("#jumlah").val(0);
            $("#deskripsi").val('');
            $("#nota").val('');
            $("#gudangTransfer1").select2("val", '0');
            $("#gudangTransfer2").select2("val", '0');

            $("#productSearchTransfer").select2("trigger", "select", {
                data: {
                    id: 0,
                    text: 'Cari Barang'
                }
            });



        }

        $("body").on("click", ".transferStock", function() {
            clear();
            $('#modalTransfer').modal('show');
        })


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

        $("#nomor").select2({
            // placeholder: 'Pilih Kategori',
            // allowClear: true,
            // dropdownParent: $('#modalStock .modal-body'),
            ajax: {
                url: "{{url('stock-transfer/no/select')}}",
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


        $("#website").select2({});

    });
</script>
@endsection