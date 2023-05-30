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
                <form action="{{url('transaksi/update')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{$transaksi->id}}">
                    <div class="row">
                        <div class="col-6 col-lg-3">
                            <div class="mb-2">
                                <label for="tgl">Tanggal <span style="color: red;">*</span></label>
                                <input name="tgl" class="form-control required" id="tgl" @if (isset($transaksi->tgl)) value="{{ date('Y-m-d', strtotime($transaksi->tgl)) }}" @endif type="date" name="tgl">
                            </div>
                        </div>
                        <div class="col-6 col-lg-3">
                            <div class="mb-2">
                                <label for="nota">No. Nota <span style="color: red;">*</span></label>
                                <input class="form-control required" id="nota" type="text" value="{{$transaksi->nomor}}" name="nota">
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
                                    <input class="form-control nilai" type="text" name="input_ppn" id="input-ppn" value="{{$transaksi->ppn}}" maxlength="2" placeholder="%" data-bs-original-title="" title="">
                                    <span class="input-group-text bg-success text-light">%</span>
                                </div>
                            </div>
                            <div class="form-group mb-2">
                                <label class="form-label" for="order_tax">PPH</label>
                                <div class="input-group">
                                    <input class="form-control nilai" type="text" name="input_pph" id="input-pph" value="{{$transaksi->pph}}" maxlength="2" placeholder="%" data-bs-original-title="" title="">
                                    <span class="input-group-text bg-success text-light">%</span>
                                </div>
                            </div>
                            <div class="form-group mb-2">
                                <label class="form-label" for="order_discount">Diskon</label>
                                <div class="input-group">
                                    <input class="form-control rupiah" type="text" name="input_diskon" id="input-diskon" value="{{$transaksi->diskon}}" placeholder="0" data-bs-original-title="" title="">
                                    <span class="input-group-text bg-success text-light">Rp</span>
                                </div>
                            </div>
                            <div class="form-group mb-2">
                                <label class="form-label" for="biayaLain">Biaya Lain-Lain</label>
                                <div class="input-group">
                                    <input class="form-control rupiah" type="text" name="biayaLain" id="biayaLain" value="{{$transaksi->biaya_lain}}" placeholder="0" data-bs-original-title="" title="">
                                    <span class="input-group-text bg-success text-light">Rp</span>
                                </div>
                            </div>
                            @if($transaksi->status_order=='dikirim')
                            <div class="col-12"><span class="text-danger">Hanya Bagian Dibawah ini Dapat Dirubah</span></div>

                            @endif

                            <h4 class="mt-2">Pengiriman <small>(optional)</small></h4>
                            <div class="border p-1 rounded mb-4">
                                <div class="row">
                                    <div class="col-12 col-lg-6">
                                        <div class="mb-2">
                                            <label for="resi">No. Resi Pengiriman</label>
                                            <input type="text" name="resi" id="resi" value="{{$transaksi->shipping->resi??''}}" class="form-control">
                                        </div>
                                        <div class="mb-2">
                                            <label for="kurir">Jasa Pengiriman</label>
                                            <input type="text" name="kurir" id="kurir" value="{{$transaksi->shipping->kurir??''}}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        <div class="mb-2">
                                            <label for="biayaPengiriman">Biaya Pengiriman</label>
                                            <input type="text" name="biayaPengiriman" id="biayaPengiriman" value="{{$transaksi->shipping->biaya??''}}" class="form-control rupiah">
                                        </div>
                                        <div class="mb-2">
                                            <label for="file">Upload Resi</label>
                                            <input type="file" name="file" id="file" class="form-control">
                                        </div>
                                        <div class="mb-2">
                                            <img id="preview-image-before-upload" alt="Preview Image" style="max-height: 100px; max-width:350px">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-4">
                            <div class="border p-1 mt-2 mt-lg-0 rounded">
                            <style>
                                .table td,
                                .table th {
                                    padding: 10px;
                                    font-size: 14px;
                                }
                            </style>
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
                            <textarea name="deskripsi" id="deskripsi" class="form-control mb-2">{{$transaksi->deskripsi}}</textarea>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                            <label for="">Status</label>
                            <select name="status_order" id="status_order" required class="form-control">
                                <option value="">Pilih Status</option>
                                @if($transaksi->status_order=='dikirim')
                                <option value="dikirim">Dikirim</option>
                                <option value="selesai">Selesai</option>
                                @else
                                <option value="dikirim">Dikirim</option>
                                <option value="proses">Proses</option>
                                <option value="selesai">Selesai</option>
                                <option value="batal">Batal</option>
                                @endif
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
                            <input type="text" class="form-control rupiah" id="bayar" name="bayar" value="{{$transaksi->bayar}}">
                            Status Bayar : <span class="status-bayar"></span>
                        </div>
                    </div>
                    <div class="mb-2">
                        <button id="btnCheckout" class="btn btn-primary btn-lg">
                            <i class="mdi mdi-cash-multiple me-1"></i>
                            Update
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

        $('#preview-image-before-upload').attr('src', '/image/transaksi/resi/' + '{{$transaksi->shipping->file??''}}');

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
                id: '{{$transaksi->sales_id}}',
                text: '{{$transaksi->sales->nama}}'
            }
        });

        $("#customer").select2("trigger", "select", {
            data: {
                id: '{{$transaksi->customer_id}}',
                text: '{{$transaksi->pelanggan->nama_depan ?? ""}} {{$transaksi->pelanggan->nama_belakang ?? ""}}'
            }
        });




        $("select[name='metode_bayar']").val('{{ $transaksi->metode_bayar }}');
        $("select[name='status_order']").val('{{ $transaksi->status_order }}');
        $("select[name='bayar']").val('{{ $transaksi->bayar }}');

    })
</script>

@endsection