<div class="dropdown edt2 mb-2">

    <label for="top-search">Masukkan Nama Produk <span style="color: red;">*</span></label>
    <input type="text" class="form-control" placeholder="Cari Produk" id="top-search">
    <div class="dropdown-menu dropdown-menu-animated dropdown-lg" id="search-dropdown"
        style="max-height: 300px; width: 100%; overflow: scroll;">

        <div id="resultSearch" class="notification-list">


        </div>
    </div>
</div>

<script>
      $('body').on('click keyup', '#top-search', function() {
                $("#resultSearch").html('')
                var nama_produk = $("#top-search").val();
                if(nama_produk==''){
                    return;
                }
                $.get("{{ url('/produk/search-with-view') }}" + '/' + nama_produk, function(data) {
                    $("#resultSearch").html(data)
                })
            });

</script>
