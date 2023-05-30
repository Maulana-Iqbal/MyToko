@extends('layouts.app')

@section('content')
@include('transaksi.cssTransaksi')
<style>
    .table input {
        padding: 5px;
    }

    #tabelCart td {
        padding: 5px;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">

            <h4 class="page-title">{{ $title }}</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{url('transaksi/simpan')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-6 col-lg-3">
                            <div class="mb-2">
                                <label for="tgl">Tanggal <span style="color: red;">*</span></label>
                                <input name="tgl" class="form-control required" id="tgl" type="date" name="tgl">
                            </div>
                        </div>
                        <div class="col-6 col-lg-3">
                            <div class="mb-2">
                                <label for="nota">No. Nota <span style="color: red;">*</span></label>
                                <input class="form-control required" id="nota" type="text" name="nota">
                            </div>
                        </div>
                        <div class="col-6 col-lg-3">
                            <label for="sales" class="control-label">Sales</label>
                            <a target="_blank" href="/sales"><small class="text-danger float-end">Buat Sales Baru</small></a>
                            <select id="sales" name="sales" required class="form-control">
                                <option value="">Pilih Sales</option>

                                </optgroup>
                            </select>
                        </div>
                        <div class="col-6 col-lg-3">
                            <div class="mb-2">
                                <label for="customer" class="control-label">Customer <span style="color: red;">*</span></label>

                                <a target="_blank" href="/customer"><small class="text-primary float-end">Buat Customer Baru</small></a>
                                <select id="customer" name="customer" required class="form-control select2">
                                </select>
                            </div>
                        </div>
                        <div class="col-6 col-lg-6">
                            <label for="gudang" class="control-label">Gudang</label>

                            <a target="_blank" href="/gudang"><small class="text-danger float-end">Buat Gudang Baru</small></a>
                            <select id="gudang" name="gudang" class="form-control select2">
                                <option value="">Pilih Gudang</option>

                                </optgroup>
                            </select>
                        </div>
                        <div class="col-6 col-lg-6">
                            <label for="produk">Pilih Barang</label>
                            <select name="produk" id="produk" class="form-control select2">
                                <option value="">Cari Barang</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <div id="produkData">

                            </div>
                        </div>
                        <div class="col-12">

                            <h4>Daftar Order</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="tabelCart" style="padding: 5px; min-width: 900px;">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Barang</th>
                                            <th>Dari Gudang</th>
                                            <th class="text-wrap">Harga</th>
                                            <th class="text-wrap">Biaya Jasa / Satuan</th>
                                            <th class="text-wrap">Harga + Jasa</th>
                                            <th>Jumlah</th>
                                            <th>Grosir</th>
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

                    <div class="row">
                        <div class="col-lg-8">
                            <div class="form-group mb-2">
                                <label class="form-label" for="order_tax">PPN</label>
                                <div class="input-group">
                                    <input class="form-control nilai" type="text" name="input_ppn" id="input-ppn" value="0" maxlength="2" placeholder="%" data-bs-original-title="" title="">
                                    <span class="input-group-text bg-success text-light">%</span>
                                </div>
                            </div>
                            <div class="form-group mb-2">
                                <label class="form-label" for="order_tax">PPH</label>
                                <div class="input-group">
                                    <input class="form-control nilai" type="text" name="input_pph" id="input-pph" value="0" maxlength="2" placeholder="%" data-bs-original-title="" title="">
                                    <span class="input-group-text bg-success text-light">%</span>
                                </div>
                            </div>
                            <div class="form-group mb-2">
                                <label class="form-label" for="order_discount">Diskon</label>
                                <div class="input-group">
                                    <input class="form-control rupiah" type="text" name="input_diskon" id="input-diskon" value="0" placeholder="0" data-bs-original-title="" title="">
                                    <span class="input-group-text bg-success text-light">Rp</span>
                                </div>
                            </div>
                            <div class="form-group mb-2">
                                <label class="form-label" for="biayaLain">Biaya Lain-Lain</label>
                                <div class="input-group">
                                    <input class="form-control rupiah" type="text" name="biayaLain" id="biayaLain" value="0" placeholder="0" data-bs-original-title="" title="">
                                    <span class="input-group-text bg-success text-light">Rp</span>
                                </div>
                            </div>

                            <h4 class="mt-4">Pengiriman <small>(optional)</small></h4>
                            <div class="border p-1 rounded mb-4">
                                @include('transaksi.modal.inputPengiriman')
                            </div>

                        </div>
                        <div class="col-lg-4">
                        <style>
                                .table td,
                                .table th {
                                    padding: 10px;
                                    font-size: 14px;
                                }
                            </style>
                            <div class="border p-1 mt-2 mt-lg-0 rounded">
                                <h4 class="mb-2">Order Summary</h4>

                                <table class="table">

                                    <tr>
                                        <td>Total Biaya Jasa</td>
                                        <td>:</td>
                                        <td align="right"><span class="totalBiaya">Rp. 0</span>
                                            <input type="hidden" name="total_biaya" id="totalBiaya">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Total Harga</td>
                                        <td>:</td>
                                        <td align="right"><span class="totalHarga">Rp. 0</span>
                                            <input type="hidden" name="total_harga" id="totalHarga">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="100px">Sub Total</td>
                                        <td width="2px">:</td>
                                        <td align="right">
                                            <span class="subTotal">Rp. 0</span>
                                            <input type="hidden" name="sub_total" id="subTotal">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>PPN</td>
                                        <td>:</td>
                                        <td align="right"><span class="ppn">Rp. 0</span><small>(+)</small></td>
                                    </tr>
                                    <tr>
                                        <td>PPH</td>
                                        <td>:</td>
                                        <td align="right"><span class="pph">Rp. 0</span><small>(+)</small></td>
                                    </tr>
                                    <tr>
                                        <td>Diskon</td>
                                        <td>:</td>
                                        <td align="right"><span class="diskon">Rp. 0</span><small>(-)</small></td>
                                    </tr>
                                    <tr>
                                        <td>Biaya Lain</td>
                                        <td>:</td>
                                        <td align="right"><span class="biayaLain">Rp. 0</span><small>(+)</small></td>
                                    </tr>
                                    <tr>
                                        <td>Biaya Pengiriman</td>
                                        <td>:</td>
                                        <td align="right"><span class="ongkir">Rp. 0</span><small>(+)</small></td>
                                    </tr>
                                    <tr>
                                        <td>Grand Total</td>
                                        <td>:</td>
                                        <td align="right">
                                            <span class="grandTotal">Rp. 0</span>
                                            <input type="hidden" name="total" id="total">
                                        </td>
                                    </tr>
                                    <!-- <tr>
                                        <td>Jumlah Diterima</td>
                                        <td>:</td>
                                        <td align="right"><span class="terimaTotal"></span></td>
                                    </tr> -->
                                </table>
                            </div>
                        </div>
                    </div>



                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <label for="deskripsi">Note:</label>
                            <textarea name="deskripsi" id="deskripsi" class="form-control mb-2"></textarea>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                            <label for="">Status</label>
                            <select name="status_order" id="status_order" required class="form-control">
                                <option value="">Pilih Status</option>
                                <option value="proses">Proses</option>
                                <option value="dikirim">Dikirim</option>
                                <option value="selesai">Selesai</option>
                                <option value="batal">Batal</option>
                            </select>
                            <small>Jika Status (Selesai / Dikirim) Stock Otomatis Dikurangi</small>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                            <label for="">Metode Bayar</label>
                            <select name="metode_bayar" id="metode_bayar" required class="form-control">
                                <option value="">Pilih Metode Bayar</option>
                                <option value="cash">Cash</option>
                                <option value="transfer">Transfer</option>
                                <option value="ewallet">E-Wallet</option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                            <label for="">Jumlah Bayar</label>
                            <input type="text" class="form-control rupiah" id="bayar" name="bayar" value="0">
                            Status Bayar : <span class="status-bayar"></span>
                        </div>
                    </div>
                    <div class="mb-2">
                        <button id="btnCheckout" class="btn btn-primary btn-lg">
                            <i class="mdi mdi-cash-multiple me-1"></i>
                            Simpan
                        </button>
                    </div>
                </form>
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div>
</div>


<script>
    $(document).ready(function() {
        $('body').attr("data-leftbar-compact-mode", "condensed");
        $('#file').change(function(e) {
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#preview-image-before-upload').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        });

        getCartTable()

        function getCartTable() {
            $.get("{{ url('/getcarttable') }}", function(data) {
                $("#tabelCart tbody").html(data)
                hitung()
            })
        }

        const formatRupiah = (money) => {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(money);
        }

        $("body").on("keyup change focusout", "#input-ppn,#input-pph, #input-diskon, #biayaPengiriman,#biayaLain, #sub-total", function() {
            hitung();
        });

        function hitung() {

            let ppnRp = 0;
            let pphRp = 0;
            let ppn = $("#input-ppn").val();
            let pph = $("#input-pph").val();
            let totalHarga = $("#totalHarga").val();
            let totalBiaya = $("#totalBiaya").val();
            let diskon = $("#input-diskon").val();
            let pengiriman = $("#biayaPengiriman").val();
            let biayaLain = $("#biayaLain").val();
            let total = 0;

            totalHarga = totalHarga.replace(/\./g, '');
            totalBiaya = totalBiaya.replace(/\./g, '');
            diskon = diskon.replace(/\./g, '');
            pengiriman = pengiriman.replace(/\./g, '');
            biayaLain = biayaLain.replace(/\./g, '');

            if (ppn == '') {
                ppn = 0;
            }
            if (pph == '') {
                pph = 0;
            }
            if (totalHarga == '') {
                totalHarga = 0;
            }
            if (totalBiaya == '') {
                totalBiaya = 0;
            }
            if (diskon == '') {
                diskon = 0;
            }
            if (biayaLain == '') {
                biayaLain = 0;
            }
            if (pengiriman == '') {
                pengiriman = 0;
            }
            if (total == '') {
                total = 0;
            }
            if (totalHarga > 0 && ppn > 0) {
                ppnRp = (parseFloat(totalHarga) / 100) * ppn;
            }
            if (totalBiaya > 0 && pph > 0) {
                pphRp = (parseFloat(totalBiaya) / 100) * pph;
            }
            total = (parseFloat(totalHarga) + parseFloat(totalBiaya) + parseFloat(ppnRp) + parseFloat(pphRp) + parseFloat(biayaLain) + parseFloat(pengiriman)) - parseFloat(diskon);
            total = Math.round(total);

            $(".ppn").html(formatRupiah(ppnRp));
            $(".pph").html(formatRupiah(pphRp));
            $(".diskon").html(formatRupiah(diskon));
            $(".ongkir").html(formatRupiah(pengiriman));
            $(".biayaLain").html(formatRupiah(biayaLain));
            $(".grandTotal").html(formatRupiah(total));
            $("#total").val(total);
            $("#bayar").val(total);
            statusBayar();
            console.log(totalHarga + totalBiaya + ppnRp + pphRp + biayaLain + pengiriman + diskon);
        }

        $("body").on("keyup change focusout", "#total,#bayar", function() {
            statusBayar();
        })

        function statusBayar() {
            let status = 'Belum Bayar';
            let bayar = $("#bayar").val();
            let total = $("#total").val();
            bayar = bayar.replace(/\./g, '');
            total = total.replace(/\./g, '');
            if (parseFloat(bayar) >= parseFloat(total)) {
                status = 'Lunas';
            } else {
                status = 'Belum Lunas';
            }
            $(".status-bayar").html(status)
            console.log()
        }

        $('body').on('change', '#produk', function() {
            $("#canvasloading").show();
            $("#loading").show();
            var produk = [];
            var gudang = [];
            $("input[name='produkId']").each(function() {
                produk.push($(this).val());
            });
            $("input[name='gudangId']").each(function() {
                gudang.push($(this).val());
            });
            var id_produk = $(this).val();
            var id_gudang = $("#gudang").val();
            // console.log('gudang dipilih '+id_gudang+' gudang ada '+gudang+' produk dipilih '+id_produk+' produk ada '+produk);

            if (id_produk == '') {
                $("#canvasloading").hide();
                $("#loading").hide();
                return;
            }

            if (produk == id_produk && gudang == id_gudang) {
                swal("Pesan", 'Produk sudah ada di Daftar Order', "error");
                $("#resultSearch").html('')
                $("#canvasloading").hide();
                $("#loading").hide();
                return;
            }

            if (id_gudang == '') {
                swal("Pesan", 'Silahkan Pilih Gudang', "error");
                $("#resultSearch").html('')
                $("#canvasloading").hide();
                $("#loading").hide();
                return;
            }

            var url = "{{ url('/cart-offline/add-to-cart') }}";

            $.ajax({
                url: url,
                data: {
                    produkId: id_produk,
                    gudangId: id_gudang
                },
                type: "POST",
                dataType: 'json',
                // contentType: false,
                // cache: false,
                // processData: false,
                success: function(data) {
                    if (data.success === true) {
                        getCartTable();
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        $("#produk").select2("trigger", "select", {
                            data: {
                                id: '',
                                text: 'Cari Produk'
                            }
                        });

                        $.NotificationApp.send("Berhasil", data.message, "top-right", "",
                            "info")
                    } else {
                        getCartTable();
                        $("#produk").select2("trigger", "select", {
                            data: {
                                id: '',
                                text: 'Cari Produk'
                            }
                        });
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        swal("Pesan", data.message, "error");
                    }
                },
                error: function(xhr) {
                    getCartTable();
                    var res = xhr.responseJSON;
                    if ($.isEmptyObject(res) == false) {
                        err = '';
                        $.each(res.errors, function(key, value) {
                            // err += value + ', ';
                            err = value;
                        });
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        $("#produk").select2("trigger", "select", {
                            data: {
                                id: '',
                                text: 'Cari Produk'
                            }
                        });
                        swal("Pesan", err, "error");
                    }
                }
            });
        });




        $("body").on("change", "#cartBiaya", function() {
            $("#canvasloading").show();
            $("#loading").show();
            var id = $(this).data('id');
            var harga = $(this).data('harga');
            var biaya = $(this).val();
            if (biaya == '') {
                biaya = 0;
            }

            $.ajax({
                type: "post",
                url: "{{ url('/cart-offline/cart-update-biaya') }}",
                data: {
                    id: id,
                    biaya: biaya,
                    harga: harga
                },
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        getCartTable()
                        hitung();
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        $.NotificationApp.send("Berhasil",
                            data.message, "top-right", "",
                            "info")
                    } else {
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        $.NotificationApp.send("Gagal",
                            data.message, "top-right", "",
                            "danger")
                    }
                },
                error: function(data) {
                    getCartTable()
                    $("#canvasloading").hide();
                    $("#loading").hide();
                    console.log('Error:', data);
                    swal("Pesan", data.message, "error");
                }
            });

        });

        //update jumlah
        $("body").on("change", "#cartJumlah", function() {
            $("#canvasloading").show();
            $("#loading").show();
            id = $(this).data('id');
            produkId = $(this).data('cartprodukid');
            gudangId = $(this).data('cartgudangid');
            jumlah = $(this).val();
            if (jumlah < 1) {
                $("#cartJumlah").val('1')
                $("#canvasloading").hide();
                $("#loading").hide();
                return;
            }

            $.ajax({
                type: "post",
                url: "{{ url('/cart-offline/cart-update-jumlah') }}",
                data: {
                    id: id,
                    produkId: produkId,
                    gudangId: gudangId,
                    jumlah: jumlah
                },
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        getCartTable()
                        hitung();
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        // swal("Pesan", data.message, "success");
                    } else {
                        getCartTable()
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        swal("Pesan", data.message, "error");
                    }
                },
                error: function(data) {
                    getCartTable()
                    $("#canvasloading").hide();
                    $("#loading").hide();

                    console.log('Error:', data);
                    swal("Pesan", data.message, "error");
                }
            });

        });


        $("#produk").select2({
            placeholder: 'Cari Produk',
            allowClear: true,
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

        $("#gudang").select2({
            placeholder: 'Pilih Gudang',
            allowClear: true,
            // dropdownParent: $('#modalStock .modal-body'),
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

        $("#sales").select2({
            placeholder: 'Pilih Sales',
            allowClear: true,
            // dropdownParent: $('#modalStock .modal-body'),
            ajax: {
                url: "/sales/select",
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

        $("#customer").select2({
            // placeholder: 'Pilih Customer',
            allowClear: true,
            // dropdownParent: $('#modalStock .modal-body'),
            ajax: {
                url: "/pelanggan/select",
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

        $("#gudang").select2("trigger", "select", {
            data: {
                id: '{{website()->default->gudang_id ?? ""}}',
                text: '{{website()->default->gudang->nama ?? ""}}'
            }
        });

        $("#sales").select2("trigger", "select", {
            data: {
                id: '{{website()->default->sales_id ?? ""}}',
                text: '{{website()->default->sales->nama ?? ""}}'
            }
        });

        $("#customer").select2("trigger", "select", {
            data: {
                id: '{{website()->default->pelanggan_id ?? ""}}',
                text: '{{website()->default->customer->nama_depan ?? ""}} {{website()->default->customer->nama_belakang ?? ""}}'
            }
        });





        // $('#createNewTrans').click(function() {
        //      CKEDITOR.instances['deskripsi'].setData('');
        //     $('#newTrans').modal('show');
        // });

        $('#filter').click(function() {
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var status_trans = $('#status_trans').val();
            var website = $('#website').val();

            $('#datatable').DataTable().destroy();
            load_data(from_date, to_date, status_trans, website);
            $("#print").val('Print');
            $("#export").val('Export');

        });

        $('#refresh').click(function() {
            $('#from_date').val('');
            $('#to_date').val('');
            $('#status_trans').val('');
            $('#website').val('');
            $('#datatable').DataTable().destroy();
            load_data();
        });




        $('body').on('click', '#btnSelesai', function() {
            id = $(this).data('id_transaksi');
            $('#selesaiForm').trigger("reset");
            $('#verifikasiId').val(id);
            $('#selesai').modal('show');
        });

        $('body').on('click', '#btnBatalkan', function() {
            id = $(this).data('id_transaksi');
            $('#batalkanForm').trigger("reset");
            $('#verifikasiIdBatal').val(id);
            $('#batalkan').modal('show');
        });

        $('body').on('click', '#btnBayar', function() {
            id = $(this).data('id_transaksi');
            kode_trans = $(this).data('kode_trans');
            total = $(this).data('total');
            $('#pembayaranForm').trigger("reset");
            $('#id_pembayaran').val(id);
            $('#kode_trans').val(kode_trans);
            $('#jml_bayar').val(total);
            $('#pembayaran').modal('show');
        });

        $('body').on('click', '#btnSavePembayaran', function(e) {
            e.preventDefault();
            $("#canvasloading").show();
            $("#loading").show();

            url = "{{ url('/pembayaran/bayar') }}";

            var form = $('#pembayaranForm')[0];
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
                        $('#pembayaranForm').trigger("reset");
                        $('#pembayaran').modal('hide');

                        $('#datatable').DataTable().destroy();
                        load_data();
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        $.NotificationApp.send("Berhasil", data.message, "top-right", "",
                            "info")
                    } else {
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        swal("Pesan", data.message, "error");
                    }
                },
                error: function(data) {
                    $("#canvasloading").hide();
                    $("#loading").hide();
                    console.log('Error:', data);
                    swal("Pesan", data.message, "error");
                }
            });
        });

        $('body').on('click', '.aksiBtn', function(e) {
            e.preventDefault();
            $("#canvasloading").show();
            $("#loading").show();
            aksi = $(this).data('id');
            if (aksi == 1) {
                var form = $('#selesaiForm')[0];
            }
            if (aksi == 2) {
                var form = $('#batalkanForm')[0];
            }

            url = "{{ url('/transaksi/verifikasi-transaksi') }}" + '/' + aksi
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
                        if (aksi == 1) {
                            $('#selesaiForm').trigger("reset");
                            $('#selesai').modal('hide');
                        }
                        if (aksi == 2) {
                            $('#batalkanForm').trigger("reset");
                            $('#batalkan').modal('hide');
                        }
                        $('#datatable').DataTable().destroy();
                        load_data();
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        $.NotificationApp.send("Berhasil", data.message, "top-right", "",
                            "info")
                    } else {
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        swal("Pesan", data.message, "error");
                    }
                },
                error: function(data) {
                    $("#canvasloading").hide();
                    $("#loading").hide();
                    console.log('Error:', data);
                    swal("Pesan", data.message, "error");
                }
            });
        });



        // $("body").on("change keyrelease", "#cartSub", function() {
        //     $("#canvasloading").show();
        //     $("#loading").show();
        //     id = $(this).data('id');
        //     harga_jual = $(this).val();

        //     $.ajax({
        //         type: "post",
        //         url: "{{ url('/cart-offline/cart-update-harga-jual') }}",
        //         data: {
        //             id: id,
        //             harga_jual: harga_jual
        //         },
        //         dataType: 'json',
        //         success: function(data) {
        //             getCartTable()
        //             $("#canvasloading").hide();
        //             $("#loading").hide();
        //             $.NotificationApp.send("Berhasil", "Harga Jual telah diperbaharui",
        //                 "top-right", "", "info")
        //         },
        //         error: function(data) {
        //             getCartTable()
        //             $("#canvasloading").hide();
        //             $("#loading").hide();
        //             console.log('Error:', data);
        //             swal("Pesan", "Pastikan Semua Data Sudah Benar", "error");
        //         }
        //     });

        // });

        kode_penjualan();

        function kode_penjualan() {
            $.ajax({
                url: "{{ url('/penjualan/kode') }}",
                type: "get",
                dataType: 'json',
                success: function(data) {
                    $("#nota").val(data.data);
                },
            });
        }






        // $("body").on("change keyrelease focusout", "#biayaLain", function() {
        //     var biayaLain = $(this).val();
        //     if (biayaLain == '' || biayaLain == 0) {
        //         $(this).val(0)
        //         return;
        //     }
        //     $("#canvasloading").show();
        //     $("#loading").show();
        //     $.ajax({
        //         type: "post",
        //         url: "{{ url('/cart-offline/cart-biaya-lain') }}",
        //         data: {
        //             biayaLain: biayaLain
        //         },
        //         dataType: 'json',
        //         success: function(data) {
        //             if (data.success) {
        //                 getCartTable();
        //                 $("#canvasloading").hide();
        //                 $("#loading").hide();
        //                 $.NotificationApp.send("Berhasil", "Biaya Lain telah diperbaharui",
        //                     "top-right", "", "info")
        //             } else {
        //                 getCartTable();
        //                 $("#canvasloading").hide();
        //                 $("#loading").hide();
        //                 swal("Pesan", data.message, "error");
        //             }
        //         },
        //         error: function(data) {
        //             $("#canvasloading").hide();
        //             $("#loading").hide();

        //             console.log('Error:', data);
        //             swal("Pesan", data.message, "error");
        //         }
        //     });
        // });

        <?php if (isset($transaksi)) { ?>
            $("body").on("change keyrelease focusout", "#biayaPengiriman", function() {
                $("#canvasloading").show();
                $("#loading").show();
                var biaya = $(this).val();

                $.ajax({
                    type: "post",
                    url: "{{ url('/cart-offline/cart-biaya-pengiriman') }}",
                    data: {
                        biaya: biaya
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.success) {
                            getCartTable();
                            $("#canvasloading").hide();
                            $("#loading").hide();
                            $.NotificationApp.send("Berhasil", "Biaya Pengiriman telah diperbaharui",
                                "top-right", "", "info")
                        } else {
                            getCartTable();
                            $("#canvasloading").hide();
                            $("#loading").hide();
                            swal("Pesan", data.message, "error");
                        }
                    },
                    error: function(data) {
                        $("#canvasloading").hide();
                        $("#loading").hide();

                        console.log('Error:', data);
                        swal("Pesan", data.message, "error");
                    }
                });
            });

        <?php } ?>

        $("#ppn").change(function() {
            if (this.checked) {
                $("#canvasloading").show();
                $("#loading").show();
                $.get("{{ url('/cart-offline/dengan-ppn') }}", function(data) {
                    getCartTable();
                    $.NotificationApp.send("Berhasil", "PPN Diterapkan", "top-right", "",
                        "info")
                })
                $("#canvasloading").hide();
                $("#loading").hide();
            } else {
                $("#canvasloading").show();
                $("#loading").show();
                $.get("{{ url('/cart-offline/hapus-ppn') }}", function(data) {
                    $(".ppn").html("Rp. 0");
                    getCartTable();
                    $.NotificationApp.send("Berhasil", "PPN Dihapus", "top-right", "", "info")
                })
                $("#canvasloading").hide();
                $("#loading").hide();
            }
        })

        $("#pph").change(function() {
            if (this.checked) {
                $("#canvasloading").show();
                $("#loading").show();
                $.get("{{ url('/cart-offline/dengan-pph') }}", function(data) {
                    getCartTable();
                    $.NotificationApp.send("Berhasil", "PPH Diterapkan", "top-right", "",
                        "info")
                })
                $("#canvasloading").hide();
                $("#loading").hide();
            } else {
                $("#canvasloading").show();
                $("#loading").show();
                $.get("{{ url('/cart-offline/hapus-pph') }}", function(data) {
                    $(".pph").html("Rp. 0");
                    getCartTable();
                    $.NotificationApp.send("Berhasil", "PPH Dihapus", "top-right", "", "info")
                })
                $("#canvasloading").hide();
                $("#loading").hide();
            }
        })





        //remove cart
        $("body").on("click", "#cartRemove", function() {
            $("#ppn").prop("checked", false);
            $("#pph").prop("checked", false);
            $(".ppn").html('');
            $(".pph").html('');
            $("#canvasloading").show();
            $("#loading").show();
            id_produk = $(this).data('id')
            $.ajax({
                type: "post",
                url: "{{ url('/cartremove') }}" + '/' + id_produk,
                success: function(data) {
                    getCartTable()
                    $("#canvasloading").hide();
                    $("#loading").hide();
                    $.NotificationApp.send("Berhasil", "Item Dihapus", "top-right", "",
                        "info")
                },
                error: function(data) {
                    getCartTable()
                    $("#canvasloading").hide();
                    $("#loading").hide();
                    console.log('Error:', data);
                    swal("Pesan", data.message, "error");
                }
            });
        })







        //list Pelanggan
        function listPelanggan() {
            $.get("{{ url('/pelanggan/list-pelanggan') }}", function(data) {
                $("#listPelanggan").html(data)
            })
        }

        listPelanggan();

        $('body').on('keyup release', '#cariPelanggan', function() {
            $("#listPelanggan").html('')
            var pelanggan = $("#cariPelanggan").val();
            $.get("{{ url('/cari-pelanggan') }}" + '/' + pelanggan, function(data) {
                $("#listPelanggan").html(data)
            })
        });




        $("body").on("focus", ".input", function() {
            val = $(this).val();
            if (val == '0') {
                $(this).val('');
                $(this).focus();
            }
        })

        $('body').on('click', '#btnVerifikasiCeo', function() {
            id = $(this).data('id_transaksi');
            $('#verifikasiFormCeo').trigger("reset");
            $('#verifikasiIdCeo').val(id);
            $('#verifikasiCeo').modal('show');
        });

        $('body').on('click', '.aksiBtnCeo', function(e) {
            e.preventDefault();

            $("#canvasloading").show();
            $("#loading").show();
            aksi = $(this).data('id');
            if (aksi == 1) {
                url = "{{ url('/verifikasi-transaksi') }}" + '/' + aksi
            } else
            if (aksi == 3) {
                url = "{{ url('/verifikasi-transaksi') }}" + '/' + aksi
            }
            var form = $('#verifikasiFormCeo')[0];
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
                        $('#verifikasiFormCeo').trigger("reset");
                        $('#verifikasiCeo').modal('hide');
                        $('#datatable').DataTable().destroy();
                        load_data();
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        $.NotificationApp.send("Berhasil", data.message, "top-right", "",
                            "info")
                    } else {
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        swal("Pesan", data.message, "error");
                    }
                },
                error: function(data) {
                    $("#canvasloading").hide();
                    $("#loading").hide();
                    console.log('Error:', data);
                    swal("Pesan", data.message, "error");
                }
            });
        });

        $('body').on('click', '#idPelanggan', function() {
            $('#pelangganId').val('');
            id = $(this).data('id');
            $('#pelangganId').val(id);
            $('#newPelanggan').modal('hide');
            var nama = $(this).html();
            $("#pelanggan-pilih").html('<h3>' + nama + '</h3>')
            $.NotificationApp.send("Info", "Pelanggan telah dipilih", "top-right", "",
                "info")
            // $('#modalProses').modal('show');
        });

        $('body').on('click', '.pengiriman', function() {
            id = $(this).data('id');
            status = $(this).data('status');
            biaya = $(this).data('biaya');
            if (status == 2) {
                $("#biayaPengiriman").val(biaya);
                $("#biayaPengiriman").prop('readonly', true);
            } else {
                $("#biayaPengiriman").val('');
                $("#biayaPengiriman").removeProp('readonly');
            }
            $('#transaksiId').val(id);
            $('#pengiriman').modal('show');
        });

        $('body').on('click', '.ubahPengiriman', function() {
            id = $(this).data('id');
            status = $(this).data('status');
            biaya = $(this).data('biaya');
            $.get("{{ url('/transaksi/ubah-pengiriman') }}" + '/' + id, function(data) {
                if (status == 2) {
                    $("#biayaPengiriman").val(biaya);
                    $("#biayaPengiriman").prop('readonly', true);
                } else {
                    $("#biayaPengiriman").val(data.biaya);
                    $("#biayaPengiriman").removeProp('readonly');
                }
                $("#resi").val(data.resi);
                $("#kurir").val(data.kurir);
                $('#transaksiId').val(data.transaksi_id);
                $('#preview-image-before-upload').attr('src', '/image/transaksi/resi' + '/' + data.file);
                $('#savePengiriman').html('Perbaharui');
                $('#pengiriman').modal('show');
            })
        });


        $('body').on('click', '#savePengiriman', function(e) {
            e.preventDefault();
            var type = $(this).html();
            if (type == 'Perbaharui') {
                var saveUrl = "{{ url('/transaksi/update-pengiriman') }}";
            } else {
                var saveUrl = "{{ url('/transaksi/simpan-pengiriman') }}";
            }
            $(this).html('Mengirim..');
            var form = $('#pengirimanForm')[0];
            var formData = new FormData(form);
            $("#canvasloading").show();
            $("#loading").show();
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

                        $('#pengirimanForm').trigger("reset");
                        $('#pengiriman').modal('hide');
                        load_data();
                        $('#savePengiriman').html('Kirim');
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        $.NotificationApp.send("Berhasil", data.message, "top-right", "",
                            "info")
                    } else {
                        $('#savePengiriman').html('Kirim');
                        $("#canvasloading").hide();
                        $("#loading").hide();
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
                        $('#savePengiriman').html('Kirim');
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        swal("Pesan", err, "error");
                    }
                }
            });
        });

        $('body').on('click', '.create-invoice', function() {
            var id = $(this).data("id");
            var shipping = $(this).data("shipping");

            myurl = "{{ url('/transaksi/create-invoice') }}" + '/' + id

            if (shipping == '1') {
                msg =
                    "Resi belum di Input, Apakah ingin membuat tagihan tanpa biaya pengiriman?";
            } else if (shipping == '2') {
                msg =
                    "Apakah ingin membuat Tagihan?";
            }
            swal({
                    title: "Buat Tagihan",
                    text: msg,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ya, Buat Tagihan!",
                    cancelButtonText: "Batal!",
                    closeOnConfirm: true,
                    closeOnCancel: true
                },
                function(isConfirm) {
                    if (isConfirm) {
                        $("#canvasloading").show();
                        $("#loading").show();

                        window.location.href = myurl;
                    } else {
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        Swal.fire({
                            title: 'Info!',
                            text: 'Buat Tagihan Dibatalkan',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        })
                    }
                });
        });




        <?php if (isset($transaksi->jenis_bayar)) { ?>
            $("select[name='jenis_bayar']").val('{{ $transaksi->jenis_bayar }}');
        <?php } ?>



    })
</script>

@endsection