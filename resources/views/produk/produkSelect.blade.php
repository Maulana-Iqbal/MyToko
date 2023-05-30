<form id="formAddToCart">
    <div class="row">
        <div class="col-lg-6">

            <input type="hidden" name="produkId" value="{{ $produk->id }}">
            <div class="mb-2 mt-2">

                <label for="nama_produk">Nama Produk</label>
                <input type="text" name="nama_produk" value="{{ $produk->nama_produk }}" readonly class="form-control">
            </div>
            <div class="mb-2 produk-group">
                <label for="stockId" class="control-label">Satuan</label>


                <select id="stockId" name="stockId" class="form-control select2" required>
                    <option value="">Pilih Satuan</option>

                    @foreach ($produk->stock as $s)
                        <option value="{{ $s->id }}">{{ $s->satuan->name }}</option>
                    @endforeach

                </select>

            </div>
            <div class="row">
                <div class="col-6">
                    <div class="mb-2">
                        <label for="harga_jual">Tentukan Harga Jual</label>
                        <input type="text" class="form-control rupiah" name="harga_jual" id="harga_jual">
                    </div>
                </div>

                <div class="col-6">
                    <div class="mb-2">
                        <label for="jumlah">Jumlah</label>
                        <input type="number" class="form-control" name="jumlah" min="1" id="jumlah">
                    </div>

                </div>
            </div>
            <div class="mb-2">
                <label for="biaya">Biaya Jasa <small>(optional)</small></label>
                <input type="text" class="form-control rupiah" name="biaya" id="biaya">
            </div>
            <div class="mb-2">
                <label for="formSubTotal">Total</label>
                <h4 id="formSubTotal"></h4>
            </div>
            <button class="btn btn-outline-primary" id="btnAddToCart">Tambahkan</button>


        </div>
        <div class="col-lg-6">
            <div class="mt-2" style="overflow: scroll;">
                <label for="">Daftar Harga</label>
                <table class="table">
                    <thead>
                        <tr>
                            <th width="15px">No</th>
                            <th width="50px">Stock</th>
                            <th>Modal</th>
                            <th>Jual</th>
                            <th>Satuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($produk->stock as $stock)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $stock->jumlah }}</td>
                                <td class="text-success">
                                    Rp.{{ number_format((float) $stock->harga, 0, ',', '.') }}
                                </td>
                                <td class="text-success">
                                    Rp.{{ number_format((float) $stock->harga_jual, 0, ',', '.') }}
                                </td>
                                <td>
                                    {{ $stock->satuan->name }}
                                </td>

                            </tr>
                            <?php $i++; ?>
                        @endforeach

                    </tbody>
                </table>
            </div>

        </div>

    </div>
</form>

<script>
    $(document).ready(function() {



        $("#stockId").select2({

        })

        getCartTable();


        function getCartTable() {
            $.get("{{ url('/getcarttable') }}", function(data) {
                $("#tabelCart tbody").html(data)

            })
        }


        $("body").on("change", "#stockId", function() {
            $('#biaya').val('');
            $('#jumlah').val('');
            $('#formSubTotal').html('');
        })

        $("body").on("change keyrelease keyup", "#harga_jual,#biaya,#jumlah", function() {

            let harga = $("#harga_jual").val();
            let biaya = $("#biaya").val();
            let jumlah = $("#jumlah").val();
            if (harga == '' || harga < 1) {
                harga = 0;
            }

            if (biaya == '' || biaya < 1) {
                biaya = 0;
            }

            if (jumlah == '' || jumlah < 1) {
                jumlah = 0;
            }

            let total = (parseFloat(harga) + parseFloat(biaya)) * parseFloat(jumlah);
            if (isNaN(total)) {
                $("#formSubTotal").html();
            } else {
                $("#formSubTotal").html('Rp.' + parseFloat(total));
            }
        })

        $('#btnAddToCart').click(function(e) {
            e.preventDefault();
            $(this).html('Menyimpan..');
            var produkId = $("#produkId").val();
            var jumlah = $("#jumlah").val();
            var harga_jual = $("#harga_jual").val();

            if (produkId == '' || jumlah == '' || harga_jual == '') {
                $(this).html('Tambahkan');
                swal("Pesan", "Data Belum Lengkap", "error");
                return;
            }
            var form = $('#formAddToCart')[0];
            var formData = new FormData(form);
            var id_produk = $("#id_produk").val();
            var url = "{{ url('/cart-offline/add-to-cart') }}";

            $("#canvasloading").show();
            $("#loading").show();
            $.ajax({
                data: formData,
                url: url,
                type: "POST",
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data.success === true) {
                        getCartTable();
                        $('#produkForm').html('');
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        $.NotificationApp.send("Berhasil", data.message, "top-right", "",
                            "info")
                    } else {
                        getCartTable();
                        $('#btnAddToCart').html('Tambahkan');
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
                        $('#btnAddToCart').html('Tambahkan');
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        swal("Pesan", err, "error");
                    }
                }
            });
        });

        $(document).on("change", "#stockId", function() {

            var stockId = $(this).val();
            if (stockId == '') {
                return;
            } else {
                $.ajax({
                    type: "post",
                    url: "{{ url('/produk/get-harga-jual') }}",
                    data: {
                        stockId: stockId,
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.success == true) {
                            if (data.data.jumlah < 1) {
                                swal("Pesan", "Stock Kosong", "error");
                            } else {
                                $("#harga_jual").val(data.data.harga_jual)
                                $("#jumlah").focus();
                            }
                        } else {
                            swal("Pesan", "Harga Jual tidak ditemukan", "error");
                        }

                        // swal("Pesan",data.message,"success");
                    }
                });
            }

        });








        // $("body").on("change keyrelease keyup", "#biaya", function() {
        //     $("#ppn").prop("checked", false);
        //     $("#pph").prop("checked", false);
        //     $(".ppn").html('');
        //     $(".pph").html('');

        //     stockId = $("#stockId").val();
        //     biaya = $(this).val();
        //     if (biaya == '' or stockId == '') {
        //         return;
        //     }
        //     $.ajax({
        //         type: "post",
        //         url: "{{ url('/cart-update-biaya') }}",
        //         data: {
        //             biaya: biaya,
        //             stockId: stockId
        //         },
        //         dataType: 'json',
        //         success: function(data) {
        //             $("#harga_jual").val(data.data.harga_jual)

        //             // swal("Pesan",data.message,"success");
        //         }
        //     });

        // });

    })
</script>
