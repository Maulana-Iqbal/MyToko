
<div class="table-responsive">
<table id="historyDatatable" class="table dt-responsive  table-striped" width="100%">
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Kode Barang</th>
            <th width="100px">Nama Barang</th>
            <th>Jenis</th>
            <th>Gudang</th>
            <th>Jumlah / Satuan</th>
            <th class="text-wrap">Stock Awal</th>
            <th class="text-wrap">Stock Akhir</th>
            <th class="text-wrap">Harga Modal Baru</th>
            <th class="text-wrap">Harga Jual Baru</th>
            <th class="text-wrap">Harga Grosir Baru</th>
            <th class="text-wrap">Harga Modal Lama</th>
            <th class="text-wrap">Harga Jual Lama</th>
            <th class="text-wrap">Harga Grosir Lama</th>
            <th>Deskripsi</th>
        </tr>
    </thead>

    <tbody>

    </tbody>
</table>
</div>


    <!-- Modal -->
    <div class="modal fade" id="verifikasiModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-info">
                    <h5 class="modal-title" id="staticBackdropLabel">Verifikasi Pengurangan Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div> <!-- end modal header -->
                <form id="verifikasiForm">
                    @csrf
                    <input type="hidden" name="verifikasiId" id="verifikasiId">
                    <div class="modal-body">
                        <label for="deskripsi">Keterangan</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keluar</button>
                        <button type="button" class="btn btn-danger aksiBtn" data-id="2">Tolak</button>
                        <button type="button" class="btn btn-primary aksiBtn" data-id="1">Setuju</button>
                    </div> <!-- end modal footer -->
                </form>
            </div> <!-- end modal content-->
        </div> <!-- end modal dialog-->
    </div> <!-- end modal-->



<script type="text/javascript">
    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


            // historyTable();

        $("body").on("click", ".historyStock", function() {
            var produk_id = $(this).data('id_produk');
            $("#filterId").val(produk_id);
            $('#historyDatatable').DataTable().destroy();
            historyTable(produk_id);
            $('#modalHistoryStock').modal('show');
        })


        function historyTable(produk_id=null, produk = null, from_date = null, to_date = null, jenis = null,website=null,gudang=null) {
            var historyTable = $('#historyDatatable').DataTable({
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
                    url: "{{ url('/history-stock/historyStockTable') }}",
                    type: "post",
                    data: {
                        produk_id:produk_id,
                        produkMutasiFilter: produk,
                        from_date: from_date,
                        to_date: to_date,
                        jenisMutasi: jenis,
                        website:website,
                        gudangMutasiFilter:gudang
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'tgl',
                        name: 'tgl'
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
                        data: 'jenis',
                        name: 'jenis'
                    },
                    {
                        data: 'gudang',
                        name: 'gudang'
                    },
                    {
                        data: 'jumlah',
                        name: 'jumlah'
                    },
                    {
                        data: 'stock_awal',
                        name: 'stock_awal'
                    },
                    {
                        data: 'stock_akhir',
                        name: 'stock_akhir'
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
                        data: 'harga_modal_lama',
                        name: 'harga_modal_lama'
                    },
                    {
                        data: 'harga_jual_lama',
                        name: 'harga_jual_lama'
                    },
                    {
                        data: 'harga_grosir_lama',
                        name: 'harga_grosir_lama'
                    },
                    {
                        data: 'deskripsi',
                        name: 'deskripsi'
                    },
                ]
            });
            historyTable.draw();
        }

        $('#filterHistory').click(function() {
            var from_date = $('#from').val();
            var to_date = $('#to').val();
            var id = $('#filterId').val();
            var produk = $('#produkMutasiFilter').val();
            var gudang = $('#gudangMutasiFilter').val();
            var jenis = $('#jenisMutasi').val();
            var website = $('#website').val();
            // if (from_date != '' && to_date != '') {
                $('#historyDatatable').DataTable().destroy();
                historyTable(id,produk,from_date,to_date,jenis,website,gudang);
                $("#print").val('Print');
                $("#export").val('Export');
            // } else {
            //     alert('Both Date is required');
            // }
        });

        $('#refreshHistory').click(function() {
            var stockId=$("#filterId").val();
            $('#from_date_history').val('');
            $('#to_date_history').val('');
            $('#jenisHistory').val('');
            $('#website').val('');
            $('#historyDatatable').DataTable().destroy();
            historyTable(stockId);
        });

        $('body').on('click', '#btnVerifikasi', function() {
                id = $(this).data('id');
                $('#verifikasiForm').trigger("reset");
                $('#verifikasiId').val(id);
                $('#verifikasiModal').modal('show');
            });


            $('.aksiBtn').click(function(e) {
                e.preventDefault();
                aksi = $(this).data('id');
                if (aksi == 1) {
                    url = "{{ url('/verifikasi-pengurangan') }}" + '/' + aksi
                } else
                if (aksi == 2) {
                    url = "{{ url('/verifikasi-pengurangan') }}" + '/' + aksi
                }
                var form = $('#verifikasiForm')[0];
                var formData = new FormData(form);
                $.ajax({
                    data: formData,
                    url: url,
                    type: "POST",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        if (data.success == true) {
                            $('#verifikasiForm').trigger("reset");
                            $('#verifikasiModal').modal('hide');
                            $('#historyDatatable').DataTable().destroy();
                            historyTable();
                            load_data();
                            $.NotificationApp.send("Berhasil", data.message, "top-right", "",
                                "info")
                        } else {
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
                            swal("Pesan", err, "error");
                        }
                    }
                });
            });


            $("#jenisHistory").select2({
                placeholder: 'Pilih Jenis',
                allowClear: true,
            });

            $("#produkMutasiFilter").select2({
            // placeholder: 'Pilih Kategori',
            // allowClear: true,
            // dropdownParent: $('#modalHistoryStock .card-body'),
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

        $("#gudangMutasiFilter").select2({
            // placeholder: 'Pilih Kategori',
            // allowClear: true,
            // dropdownParent: $('#modalHistoryStock .card-body'),
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


        $("#jenisMutasi").select2({});



            $('.input-daterange').datepicker({
            todayBtn: 'linked',
            format: 'yyyy-mm-dd',
            autoclose: true
        });
        

    });
</script>
