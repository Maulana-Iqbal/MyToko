
                    <div class="card-body">
                        @if (session()->has('success'))
                            <div class="alert alert-success mb-2" role="alert">
                                {{ session()->get('success') }}
                            </div>
                        @endif
                        @if (session()->has('error'))
                            <div class="alert alert-danger mb-2" role="alert">
                                {{ session()->get('error') }}
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger mb-2">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
<div class="row">
    <div class="col-md-6">
        <div class="mb-2">
            <label for="tgl_bayar">Tanggal Pembayaran</label>
            <input type="date" name="tgl_bayar" id="tgl_bayar" required class="form-control">
        </div>
        <div class="mb-2">
            <label for="kode_trans">No Transaksi</label>
            <input type="text" name="kode_trans" id="kode_trans" required class="form-control">
        </div>
        <div class="mb-2">
            <label for="jml_bayar">Jumlah Bayar</label>
            <input type="text" name="jml_bayar" id="jml_bayar" required class="form-control rupiah">
        </div>
        <div class="mb-2">
            <label for="metode_bayar">Metode Bayar</label>
            <select name="metode_bayar" id="metode_bayar" class="form-control">
                <option value="">Pilih Metode Bayar</option>
                @if(auth()->user()->level)
                <option value="1">Bayar Cash</option>
                @endif
                <option value="2">Transfer Bank</option>
                <option value="3">Virtual Akun</option>
            </select>
        </div>
        
        
    </div>
    <div class="col-md-6">
        <div class="mb-2">
            <label for="nama_bank">Nama Bank</label>
            <input type="text" name="nama_bank" id="nama_bank" required class="form-control">
        </div>
        <div class="mb-2">
            <label for="nama_rek">Nama Rekening</label>
            <input type="text" name="nama_rek" id="nama_rek" required class="form-control">
        </div>
        <div class="mb-2">
            <label for="no_rek">Nomor Rekening</label>
            <input type="text" name="no_rek" id="no_rek" required class="form-control">
        </div>
        <div class="mb-2">
            <label for="file">Upload Bukti Pembayaran</label>
            <input type="file" name="file" id="file" @if(!session()->has('level')) required @endif class="form-control">
        </div>
    </div>
    <div class="col-md-12">
        <div class="mb-2">
            <label for="deskripsi">Keterangan</label>
            <textarea name="deskripsi" id="deskripsi" class="form-control"></textarea>
        </div>
    </div>
</div>
                        
                    </div>
                        <div class="float-end mb-2 me-2">
                            <button type="reset" class="btn btn-outline-danger me-2" data-bs-dismiss="modal">Batal</button> <button type="submit" id="btnSavePembayaran"
                                class="btn btn-outline-primary">Kirim</button>
                        </div>