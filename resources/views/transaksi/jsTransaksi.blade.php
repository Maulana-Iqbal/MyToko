<script type="text/javascript">
    $(document).ready(function() {
        $('#filter').click(function() {
            var from = $('#from').val();
            var to = $('#to').val();
            var nomor = $('#nomor').val();
            var sales = $('#sales').val();
            var customer = $('#customer').val();
            var status_bayar = $('#status-bayar').val();
            var status_order = $('#status-order').val();
            var website = $('#website').val();

            $('#datatable').DataTable().destroy();
            load_data(from, to, nomor, sales, customer, status_bayar, status_order, website);
            $("#print").val('Print');
            $("#export").val('Export');

        });

        $('#refresh').click(function() {
            $('#form-filter').trigger("reset");
            $("#nomor").val('').trigger('change');
            $("#sales").val('').trigger('change');
            $("#customer").val('').trigger('change');
            $("#website").val('').trigger('change');
            $("#from").val('');
            $("#to").val('');
            $("#status-bayar").val('');
            $("#status-order").val('');
            $('#datatable').DataTable().destroy();
            load_data();
        });

        load_data();

        function load_data(from = null, to = null, nomor = null, sales = null, customer = null, status_bayar = null, status_order = null, website = null) {
            <?php $status=''; if (isset($status)) { ?>
                var status = "{{ $status }}";
            <?php } ?>
            if (status != '') {
                var urlTrans = "{{ url('/transaksi/table') }}" + '/' + status;
            } else {
                var urlTrans = "{{ url('/transaksi/table') }}";
            }
            var table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                retrieve: true,
                paging: true,
                destroy: true,
                "scrollX": false,
                ajax: {
                    url: urlTrans,
                    type: "POST",
                    data: {
                        from_date: from,
                        to_date: to,
                        nomor: nomor,
                        sales: sales,
                        customer: customer,
                        status_bayar: status_bayar,
                        status_order: status_order,
                        website: website,
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
                        data: 'nomor',
                        name: 'nomor'
                    },
                    {
                        data: 'customer',
                        name: 'customer'
                    },
                    {
                        data: 'perusahaan',
                        name: 'perusahaan'
                    },
                    {
                        data: 'total',
                        name: 'total'
                    },
                    {
                        data: 'metode_bayar',
                        name: 'metode_bayar'
                    },
                    {
                        data: 'status_order',
                        name: 'status_order'
                    },
                    {
                        data: 'deskripsi',
                        name: 'deskripsi',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'toko',
                        name: 'toko'
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

        $("#sales").select2({
            // placeholder: 'Sales Umum',
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
            // placeholder: 'Sales Umum',
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

        $("#website").select2({
            allowClear: true,
        })

        $("#status-bayar").select2({
            allowClear: true,
        })

        $("#status-order").select2({
            allowClear: true,
        })

        $("#nomor").select2({
            // placeholder: 'Pilih Kategori',
            // allowClear: true,
            // dropdownParent: $('#modalStock .modal-body'),
            ajax: {
                url: "{{url('penjualan/no/select')}}",
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
    });
</script>