<form action="/stock/laporan" target="_blank" method="post">
    @csrf
    <div class="row">
        <div class="col-md-9">
            <div class="row">
           
                <div class="col-md-4">
                    <label for="productFilter">Cari Barang</label>
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
                @if(auth()->user()->can('stock') or auth()->user()->can('stock-laporan-semua-stock'))
                <button type="submit" name="print" value="Print" class="btn btn-outline-success mb-2"><i class="mdi mdi-printer"></i> Print</button>
                <button type="submit" name="export" value="Excel" class="btn btn-outline-warning mb-2"><i class="mdi mdi-file-excel-box"></i> Excel</button>
                <button type="submit" name="pdf" value="PDF" class="btn btn-outline-danger mb-2"><i class="mdi mdi-file-pdf-box"></i> Pdf</button>
                @endif
                 <!-- <div class="float-end">
                            <a target="_blank" href="/laporan/print-laporan-stock?print=true" class="btn btn-outline-success mb-2"><i class="mdi mdi-printer"></i> Print</a>
                            <a target="_blank" href="/laporan/print-laporan-stock?export=true" class="btn btn-outline-warning mb-2"><i class="mdi mdi-file-excel-box"></i> Excel</a>
                            <a target="_blank" href="/laporan/print-laporan-stock?pdf=true" class="btn btn-outline-danger mb-2"><i class="mdi mdi-file-pdf-box"></i> Pdf</a>
                        </div> -->
            </div>
        </div>
    </div>
</form>
<br>