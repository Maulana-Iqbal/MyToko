@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Ubah Pembelian</h4>
        </div>
        <form action="{{url('pembelian/update')}}" method="post">
            @csrf
            <input type="hidden" name="id" id="id" value="{{$data->id}}">
            <div class="card">
                <div class="card-body">
                    @if(session('alert')=='error')
                    <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <strong>Error - </strong> {{session('message')}}
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="mb-2">
                                <label for="tgl">Tanggal <span style="color: red;">*</span></label>
                                <input class="form-control" id="tgl" type="date" value="{{$data->tgl}}" required name="tgl">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="mb-2">
                                <label for="nota">No. Nota <span style="color: red;">*</span></label>
                                <input type="text" name="nota" id="nota" value="{{$data->nomor}}" required class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="mb-2">
                                <label for="supplier" class="control-label">Supplier <span style="color: red;">*</span></label>

                                <a target="_blank" href="/supplier"><small class="text-primary float-end">Buat Supplier Baru</small></a>
                                <select id="supplier" name="supplier" required class="form-control select2">
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="mb-2">

                                <label for="gudang" class="control-label">Gudang</label>

                                <a target="_blank" href="/gudang"><small class="text-primary float-end">Buat Gudang Baru</small></a>
                                <select id="gudang" name="gudang" class="form-control select2">
                                    <option value="">Pilih Gudang</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
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

                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="cart-table" class="table table-bordered" style="min-width: 900px;">
                                    <thead class="table-light">
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Barang</th>
                                        <th>Gudang</th>
                                        <th>Harga Modal<br><small>(<a target="_blank" href="{{url('stock')}}">Ubah Harga</a>)</small></th>
                                        <th>Jumlah</th>
                                        <!-- <th>Grosir</th> -->
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
                        <div class="col-lg-5">
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
                                    <input class="form-control rupiah" type="text" value="{{$data->diskon}}" name="input-diskon" id="input-diskon" placeholder="0" data-bs-original-title="" title="">
                                    <span class="input-group-text bg-success text-light">Rp</span>
                                </div>
                                <div class="txt-danger small empty-discount d-none">Please enter valid discount value.</div>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label" for="order_shipping">Biaya Pengiriman</label>
                                <div class="input-group">
                                    <input class="form-control rupiah" type="text" value="{{$data->pengiriman}}" name="input-pengiriman" id="input-pengiriman" placeholder="0" data-bs-original-title="" title="">
                                    <span class="input-group-text bg-success text-light">Rp</span>
                                </div>
                                <div class="txt-danger small empty-shipping d-none">Please enter valid shipping value.</div>
                            </div>
                        </div>
                        <div class="col-lg-2"></div>
                        <div class="col-lg-5">
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
                                                <span id="order_total_cell" class="total-cart">{{uang($data->order_total)}}</span>
                                                <input type="hidden" name="sub_total" value="{{$data->order_total}}" id="sub-total">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>PPN</th>
                                            <td>
                                                <span id="order_tax_cell">{{uang($data->tax)}} </span><span id="order_tax_cell_per">(0%)</span>
                                                <input type="hidden" name="ppn" value="{{$data->tax}}" id="ppn">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Pengiriman</th>
                                            <td><span id="shipping_cell">{{uang($data->pengiriman)}}</span></td>
                                            <input type="hidden" name="pengiriman" value="{{$data->pengiriman}}" id="pengiriman">
                                        </tr>
                                        <tr>
                                            <th>Diskon</th>
                                            <td><span id="discount_cell">{{uang($data->diskon)}}</span></td>
                                            <input type="hidden" name="diskon" value="{{$data->diskon}}" id="diskon">
                                        </tr>
                                        <tr>
                                            <th class="txt-primary">Grand Total</th>
                                            <td><span id="grand_total_cell">{{$data->total}}</span></td>
                                            <input type="hidden" name="total" value="{{$data->total}}" id="total">
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                    <div class="col-12">
                            <label for="deskripsi">Note:</label>
                            <textarea name="deskripsi" id="deskripsi" class="form-control mb-2">{{$data->deskripsi}}</textarea>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <label for="">Status</label>
                            <select name="status_order" id="status_order" required class="form-control">
                                <option value="">Pilih Status</option>
                                <option value="dikirim">Dikirim</option>
                                <option value="proses">Proses</option>
                                <option value="selesai">Selesai</option>
                                <option value="batal">Batal</option>
                            </select>
                            <small>Jika Status Selesai Barang Otomatis Ditambahkan ke Stock</small>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <label for="">Metode Bayar</label>
                            <select name="metode_bayar" id="metode_bayar" required class="form-control">
                                <option value="">Pilih Metode Bayar</option>
                                <option value="cash">Cash</option>
                                <option value="transfer">Transfer</option>
                                <option value="ewallet">E-Wallet</option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <label for="">Jumlah Bayar</label>
                            <input type="text" class="form-control rupiah" name="bayar" id="bayar" value="{{$data->bayar}}" autocomplete="off">
                            Status Bayar : <span class="status-bayar"></span>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" id="simpan" class="btn btn-primary">Perbarui</button>
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
        $("form").submit(function(e) {
            var bayar=$("#bayar").val();
            bayar=bayar.replace(/\./g,'');
            var total=$("#total").val();
            var status=$("#status_order").val();
            if(status==='selesai' && parseFloat(bayar)<parseFloat(total)){
                alert('Jika Status Selesai, Jumlah Bayar tidak boleh Kecil Dari Grand Total')
                return false;
            }
            return true;
        });

        getCartTable();

        function getCartTable() {
            $(".tbody").load("{{url('pembelian/cart-table')}}", function() {
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

        $("#supplier").select2({
            placeholder: 'Pilih Supplier',
            allowClear: true,
            // dropdownParent: $('#modalStock .modal-body'),
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
                swal("Pesan", 'Barang sudah ada di Daftar Order', "error");
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
                $("#produk").select2("trigger", "select", {
                    data: {
                        id: '',
                        text: 'Cari Barang'
                    }
                });
                return;
            }

            var url = "{{ url('/pembelian/cart-add') }}";

            $.ajax({
                url: url,
                data: {
                    produkId: id_produk,
                    gudangId: id_gudang
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
                url: "{{ url('/pembelian/cart-update-jumlah') }}",
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
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        // swal("Pesan", data.message, "success");
                        $.NotificationApp.send("Berhasil", data.message, "top-right", "",
                        "info")
                    } else {
                        $("#cartJumlah").val(data.data.jumlah);
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
                url: "{{ url('/pembelian/cart-remove') }}" + '/' + id_produk,
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

        $("body").on("keyup change focusout", "#input-ppn, #input-diskon, #input-pengiriman, #sub-total, #cartJumlah", function() {
            hitung();
        });

        function hitung() {
            let ppnRp = 0;
            let ppn = $("#input-ppn").val();
            let subTotal = $("#sub-total").val();
            let diskon = $("#input-diskon").val();
            let pengiriman = $("#input-pengiriman").val();

            subTotal = subTotal.replace(/\./g,'');
            diskon = diskon.replace(/\./g,'');
            pengiriman = pengiriman.replace(/\./g,'');

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
            // console.log(ppn + '/' + ppnRp + '/' + subTotal + '/' + total)
            

        }

        var ppnTotal = 0;
        var ppn = '{{$data->tax}}';
        var order_total = '{{$data->order_total}}';
        ppnTotal = (ppn / order_total) * 100;
        $("#input-ppn").val(ppnTotal);
        // hitung();

        $("body").on("keyup change focusout","#total,#bayar",function(){
            statusBayar();
        })

        function statusBayar(){
            let status='Belum Bayar';
            let bayar=$("#bayar").val();
            let total=$("#total").val();
            bayar = bayar.replace(/\./g,'');
            total = total.replace(/\./g, '');
            if (parseFloat(bayar) >= parseFloat(total)) {
                status = 'Lunas';
            } else {
                status = 'Belum Lunas';
            }
            $(".status-bayar").html(status)
            console.log(status)
        }

        // kode_pembelian();

        // function kode_pembelian() {
        //     $.ajax({
        //         url: "{{ url('/pembelian/kode') }}",
        //         type: "get",
        //         dataType: 'json',
        //         success: function(data) {
        //             $("#nota").val(data.data);
        //         },
        //     });
        // }

        $("#supplier").select2("trigger", "select", {
            data: {
                id: '{{$data->pemasok_id}}',
                text: '{{$data->pemasok->nama}}'
            }
        });

        $("#gudang").select2("trigger", "select", {
            data: {
                id: '{{website()->default->gudang_id ?? ""}}',
                text: '{{website()->default->gudang->nama ?? ""}}'
            }
        });

        $("#status_order").val('{{$data->status_order}}')
        $("#metode_bayar").val('{{$data->metode_bayar}}')


    })
</script>
@endsection