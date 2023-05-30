@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Transfer Barang</h4>
        </div>
        <form action="{{url('stock-transfer/store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body">
                    @if(session('alert')=='error')
                    <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <strong>Error - </strong> {{session('message')}}
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="mb-2">
                                <label for="tgl">Tanggal <span style="color: red;">*</span></label>
                                <input class="form-control" id="tgl" type="date" required name="tgl">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="mb-2">
                                <label for="nota">No. Nota <span style="color: red;">*</span></label>
                                <input type="text" name="nota" id="nota" required class="form-control">
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="mb-2">

                                <label for="gudang" class="control-label">Dari Gudang</label>

                                <a target="_blank" href="/gudang"><small class="text-primary float-end">Buat Gudang Baru</small></a>
                                <select id="gudang" name="gudang" class="form-control select2">
                                    <option value="">Dari Gudang</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="mb-2">

                                <label for="gudang2" class="control-label">Ke Gudang</label>

                                <a target="_blank" href="/gudang"><small class="text-primary float-end">Buat Gudang Baru</small></a>
                                <select id="gudang2" name="gudang2" class="form-control select2">
                                    <option value="">Ke Gudang</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="mb-2">
                                <div class="mb-2">
                                    <label for="produk">Cari Barang</label>
                                    <a target="_blank" href="/produk"><small class="text-primary float-end">Buat Barang Baru</small></a>
                                    <select name="produk" id="produk" class="form-control">
                                        <option value="">Barang</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table id="cart-table" class="table table-bordered" style="min-width: 900px;">
                                    <thead class="table-light">
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Barang</th>
                                        <!-- <th>Dari Gudang</th>
                                        <th>Ke Gudang</th> -->
                                        <th>Harga Modal<br><small>(<a target="_blank" href="{{url('stock')}}">Ubah Harga</a>)</small></th>
                                        <th>Jumlah</th>
                                        <th>Total</th>
                                        <th>Aksi</th>
                                    </thead>
                                    <tbody class="tbody">

                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-5">
                            <div class="form-group mb-3">
                                <label class="form-label" for="order_tax">PPN</label>
                                <div class="input-group">
                                    <input class="form-control nilai" type="text" name="input-ppn" id="input-ppn" value="0" maxlength="2" placeholder="%" data-bs-original-title="" title="">
                                    <span class="input-group-text bg-success text-light">%</span>
                                </div>
                                <div class="txt-danger small empty-tax d-none">Please enter valid tax value.</div>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label" for="order_discount">Diskon</label>
                                <div class="input-group">
                                    <input class="form-control rupiah" type="text" name="input-diskon" id="input-diskon" value="0" placeholder="0" data-bs-original-title="" title="">
                                    <span class="input-group-text bg-success text-light">Rp</span>
                                </div>
                                <div class="txt-danger small empty-discount d-none">Please enter valid discount value.</div>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label" for="order_shipping">Biaya Pengiriman</label>
                                <div class="input-group">
                                    <input class="form-control rupiah" type="text" name="input-pengiriman" id="input-pengiriman" value="0" placeholder="0" data-bs-original-title="" title="">
                                    <span class="input-group-text bg-success text-light">Rp</span>
                                </div>
                                <div class="txt-danger small empty-shipping d-none">Please enter valid shipping value.</div>
                            </div>
                        </div>
                        <div class="col-md-2"></div>
                        <div class="col-md-5">
                        <style>
                                .table td,
                                .table th {
                                    padding: 10px;
                                    font-size: 14px;
                                }
                            </style>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-stripped">
                                    <tbody>
                                        <tr>
                                            <th>Total</th>
                                            <td>
                                                <span id="order_total_cell" class="total-cart">Rp.0</span>
                                                <input type="hidden" name="sub_total" id="sub-total">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>PPN</th>
                                            <td>
                                                <span id="order_tax_cell">0 </span><span id="order_tax_cell_per">(0%)</span>
                                                <input type="hidden" name="ppn" id="ppn">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Pengiriman</th>
                                            <td><span id="shipping_cell">0</span></td>
                                            <input type="hidden" name="pengiriman" id="pengiriman">
                                        </tr>
                                        <tr>
                                            <th>Diskon</th>
                                            <td><span id="discount_cell">0</span></td>
                                            <input type="hidden" name="diskon" id="diskon">
                                        </tr>
                                        <tr>
                                            <th class="txt-primary">Grand Total</th>
                                            <td><span id="grand_total_cell">0</span></td>
                                            <input type="hidden" name="total" id="total">
                                        </tr>
                                    </tbody>
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
                                <option value="batal">Batal</option>
                            </select>
                            <small>Stock Otomatis Dikurangi Setelah <b>Dikirim</b></small>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6" id="upload">
                            <label for="">Upload Bukti Kirim</label>
                            <input type="file" name="file" id="file" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" id="simpan" class="btn btn-primary">Simpan</button>
                    <button type="reset" id="reset" class="btn btn-danger">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        const formatRupiah = (money) => {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(money);
        }

        $("#upload").hide();
        $("#file").removeAttr('required');
        $(document).on("change", "#status_order", function() {
            if ($(this).val() == 'dikirim') {
                $("#upload").show();
                $("#file").attr('required', true);
            } else {
                $("#upload").hide();
                $("#file").removeAttr('required');
            }
        })

        getCartTable();

        function getCartTable() {
            $(".tbody").load("{{url('stock-transfer/cart-table')}}", function() {
                hitung();
            });
        }

        $("#produk").select2({
            placeholder: 'Cari Barang',
            allowClear: true,
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

        $("#gudang").select2({
            placeholder: 'Dari Gudang',
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

        $("#gudang").select2("trigger", "select", {
            data: {
                id: '{{website()->default->gudang_id ?? ""}}',
                text: '{{website()->default->gudang->nama ?? ""}}'
            }
        });

        $("#gudang2").select2({
            placeholder: 'Ke Gudang',
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


        $('body').on('change', '#produk', function() {
            $("#canvasloading").show();
            $("#loading").show();
            var produk = '';
            var dari = '';
            var ke = '';
            $("input[name='produkId']").each(function() {
                produk = $(this).val();
            });
            $("input[name='dari']").each(function() {
                dari = $(this).val();
            });
            $("input[name='ke']").each(function() {
                ke = $(this).val();
            });
            var id_produk = $(this).val();
            var id_dari = $("#gudang").val();
            var id_ke = $("#gudang2").val();
            // console.log('gudang dipilih '+id_gudang+' gudang ada '+gudang+' produk dipilih '+id_produk+' produk ada '+produk);

            if (id_produk == '') {
                $("#canvasloading").hide();
                $("#loading").hide();
                return;
            }
            console.log('produk ' + produk + '/' + id_produk + ' gudang1 ' + dari + '/' + id_dari + ' gudang2 ' + ke + '/' + id_ke);


            if (id_dari == '' || id_ke == '') {
                swal("Pesan", 'Silahkan Pilih Gudang', "error");
                $("#resultSearch").html('')
                $("#canvasloading").hide();
                $("#loading").hide();
                $("#produk").select2("trigger", "select", {
                    data: {
                        id: '',
                        text: 'Cari Barang'
                    }
                });
                return;
            }

            if (id_dari == id_ke) {
                swal("Pesan", 'Tidak Bisa Transfer Ke Gudang yang Sama', "error");
                $("#resultSearch").html('')
                $("#canvasloading").hide();
                $("#loading").hide();
                $("#produk").select2("trigger", "select", {
                    data: {
                        id: '',
                        text: 'Cari Barang'
                    }
                });
                return;
            }
            if (produk != '') {
                if (id_dari != dari || id_ke != ke) {
                    swal("Pesan", 'Gudang Asal dan Tujuan Harus sama dengan yang ada di Daftar Transfer', "error");
                    $("#resultSearch").html('')
                    $("#canvasloading").hide();
                    $("#loading").hide();
                    $("#produk").select2("trigger", "select", {
                        data: {
                            id: '',
                            text: 'Cari Barang'
                        }
                    });
                    return;
                }
            }

            produk = [];
            dari = [];
            ke = [];

            $("input[name='produkId']").each(function() {
                produk.push($(this).val());
            });
            $("input[name='dari']").each(function() {
                dari.push($(this).val());
            });
            $("input[name='ke']").each(function() {
                ke.push($(this).val());
            });

            var url = "{{ url('/stock-transfer/cart-add') }}";

            $.ajax({
                url: url,
                data: {
                    produkId: id_produk,
                    dari: id_dari,
                    ke: id_ke,
                },
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    if (data.success === true) {
                        getCartTable();
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        $("#produk").select2("trigger", "select", {
                            data: {
                                id: '',
                                text: 'Cari Barang'
                            }
                        });
                        $.NotificationApp.send("Berhasil", data.message, "top-right", "",
                            "info")
                    } else {
                        getCartTable();
                        $("#produk").select2("trigger", "select", {
                            data: {
                                id: '',
                                text: 'Cari Barang'
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
                                text: 'Cari Barang'
                            }
                        });
                        swal("Pesan", err, "error");
                    }
                }
            });
        });

        //update jumlah
        $("body").on("change", "#cartJumlah", function(e) {
            $("#canvasloading").show();
            $("#loading").show();
            id = $(this).data('id');
            produkId = $(this).data('cartprodukid');
            dari = $(this).data('cartdari');
            ke = $(this).data('cartke');
            jumlah = $(this).val();
            if (jumlah < 1) {
                $("#cartJumlah").val('1')
                $("#canvasloading").hide();
                $("#loading").hide();
                return;
            }

            $.ajax({
                type: "post",
                url: "{{ url('/stock-transfer/cart-update-jumlah') }}",
                data: {
                    id: id,
                    produkId: produkId,
                    dari: dari,
                    ke: ke,
                    jumlah: jumlah
                },
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        getCartTable()
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        // swal("Pesan", data.message, "success");
                        $.NotificationApp.send("Berhasil", data.message, "top-right", "",
                            "info")
                    } else {
                        $(e.target).val(1);
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
            $("#canvasloading").show();
            $("#loading").show();
            id_produk = $(this).data('id')
            $.ajax({
                type: "post",
                url: "{{ url('/stock-transfer/cart-remove') }}" + '/' + id_produk,
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

        $("body").on("keyup change focusout", "#input-ppn, #input-diskon, #input-pengiriman, #sub-total", function() {
            hitung();
        });

        function hitung() {
            let ppnRp = 0;
            let ppn = $("#input-ppn").val();
            let subTotal = $("#sub-total").val();
            let diskon = $("#input-diskon").val();
            let pengiriman = $("#input-pengiriman").val();

            subTotal = subTotal.replace(/\./g, '');
            diskon = diskon.replace(/\./g, '');
            pengiriman = pengiriman.replace(/\./g, '');

            if (ppn == '') {
                ppn = 0;
            }
            if (subTotal == '') {
                subTotal = 0;
            }
            if (diskon == '') {
                diskon = 0;
            }
            if (pengiriman == '') {
                pengiriman = 0;
            }
            let total = $("#total").val();

            ppnRp = (parseFloat(subTotal) / 100) * ppn;
            total = (parseFloat(subTotal) + parseFloat(ppnRp) + parseFloat(pengiriman)) - parseFloat(diskon);
            total = Math.round(total);
            $("#ppn").val(ppnRp);
            $("#diskon").val(diskon);
            $("#pengiriman").val(pengiriman);
            $("#total").val(total);

            $("#order_tax_cell").html(formatRupiah(ppnRp));
            $("#order_tax_cell_per").html(' (' + ppn + '%)');
            $("#discount_cell").html(formatRupiah(diskon) + ' (-)');
            $("#shipping_cell").html(formatRupiah(pengiriman));
            $("#grand_total_cell").html(formatRupiah(total));

            console.log(ppn + '/' + ppnRp + '/' + subTotal + '/' + total)

        }


        kode_stocktransfer();

        function kode_stocktransfer() {
            $.ajax({
                url: "{{ url('/stock-transfer/kode') }}",
                type: "get",
                dataType: 'json',
                success: function(data) {
                    $("#nota").val(data.data);
                },
            });
        }

    })
</script>
@endsection