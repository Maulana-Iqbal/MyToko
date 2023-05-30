<form action="/print-laporan-transaksi" target="_blank" method="get">
    @csrf
<input type="hidden" name="status" @if(isset($status)) value="{{$status}}" @endif>
    <h4>Filter</h4>
    <div class="row">
        <div class="col-md-9 input-daterange">
            <div class="row">
                <div class="col align-self-start">
                    <label for="from_date">Dari Tanggal</label>
                    <input type="text" name="from_date" id="from_date" class="form-control"
                        placeholder="From Date" readonly />
                </div>
                <div class="col align-self-end">
                    <label for="to_date input-daterange">Sampai Tanggal</label>
                    <input type="text" name="to_date" id="to_date" class="form-control"
                        placeholder="To Date" readonly />
                </div>
            </div>
            @if(!isset($status) or empty($status))
            <div class="col align-self-start">
                <label for="status_trans">Status Transaksi</label>
                <select name="status_trans" id="status_trans" class="form-control">
                    <option value="">Semua</option>
                    <option value="1">Menunggu / Pembayaran / Verifikasi</option>
                    <option value="2">Proses</option>
                    <option value="3">Selesai</option>
                    <option value="4">Dibatalkan</option>
                    <option value="5">Dibayar</option>
                    <option value="6">Dikirim</option>
                </select>
            </div>
            @endif

            @if (auth()->user()->level == 'SUPERADMIN' or auth()->user()->level == 'SUPERCEO')
                                            <div class="row">
                                                <div class="col">
                                                    <label for="website">Perusahaan</label>
                                                    <select name="website" id="website" class="form-control">
                                                        <option value="">Semua</option>
                                                        @foreach (dataWebsite() as $item)
                                                            <option value="{{ $item->id }}">{{ $item->nama_website }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @endif
            
        </div>

        <div class="col align-self-end">
            <label for="">Aksi</label>
            <div class="form-group">
                <a id="filter" class="btn btn-outline-primary mb-2">Filter</a>
                <a id="refresh" class="btn btn-outline-info mb-2">Refresh</a><br>
                <input type="submit" name="print" value="Print" class="btn btn-outline-success">
                <input type="submit" name="export" value="Excel" class="btn btn-outline-warning">
                <input type="submit" name="pdf" value="PDF" class="btn btn-outline-danger">
            </div>
        </div>
    </div>
</form>
