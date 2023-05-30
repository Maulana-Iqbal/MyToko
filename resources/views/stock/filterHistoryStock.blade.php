<form action="/mutasi/laporan" target="_blank" method="post">
    @csrf
    <input type="hidden" name="filterId" id="filterId">
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
                    <label for="produkMutasiFilter">Cari Barang</label>
                    <select multiple name="produkMutasiFilter[]" id="produkMutasiFilter" class="form-control">
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="gudangMutasiFilter" class="control-label">Gudang</label>
                    <select multiple name="gudangMutasiFilter[]" id="gudangMutasiFilter" class="form-control">
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="jenisMutasi">Jenis Stock</label>
                    <select multiple name="jenisMutasi[]" id="jenisMutasi" class="form-control">
                        <option value="1">Masuk</option>
                        <option value="2">Keluar</option>
                        <option value="3">Pengurangan</option>
                        <option value="4">Ubah Harga</option>
                        <option value="5">Transfer Dari</option>
                        <option value="6">Transfer Ke</option>
                    </select>
                </div>
                
                @can('show-all')

                <div class="col-md-4">
                    <label for="website">Toko</label>
                    <select name="website" id="website" class="form-control">
                        <option value="">Semua</option>
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
                <a id="filterHistory" class="btn btn-outline-primary mb-2">Filter</a>
                <a id="refreshHistory" class="btn btn-outline-info mb-2">Reset</a><br>
                <input type="submit" name="print" value="Print" class="btn btn-outline-success">
                <input type="submit" name="export" value="Excel" class="btn btn-outline-warning">
                <input type="submit" name="pdf" value="PDF" class="btn btn-outline-danger">
            </div>
        </div>
    </div>
</form>
<br />