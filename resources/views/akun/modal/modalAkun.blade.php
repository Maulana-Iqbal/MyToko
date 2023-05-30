<div id="ajaxModel" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog"
    aria-labelledby="success-header-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-info">
                <h4 class="modal-title" id="success-header-modalLabel">Akun</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form id="akunForm" name="akunForm" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id_akun" id="id_akun">
                    <div class="mb-2 akun-group">
                        <label for="tipe" class="control-label">Tipe <span
                                style="color: red;">*</span></label>
                        <select name="tipe" id="tipe" class="form-control">
                            <option value="detail">Detail</option>
                            <option value="header">Header</option>
                           </select>
                    </div>
                    <div class="mb-2 akun-group">
                        <label for="kategori_akun_id" class="control-label">Kategori Akun <span
                                style="color: red;">*</span></label>
                        <select name="kategori_akun_id" id="kategori_akun_id" class="form-control">
                            <option value="">Pilih Kategori Akun</option>
                            @foreach($kategori_akun as $kategori_akun)
                            <option value="{{$kategori_akun->id}}">{{$kategori_akun->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2 akun-group">
                        <label for="kode" class="col-sm-3 control-label">Kode Akun <span
                                style="color: red;">*</span></label>
                        <input type="text" class="form-control" id="kode" name="kode"
                            placeholder="Masukkan Kode Akun" required="">
                    </div>

                    <div class="mb-2 akun-group">
                        <label for="name" class="col-sm-3 control-label">Nama Akun <span
                                style="color: red;">*</span></label>
                        <input type="text" class="form-control" id="name" name="name"
                            placeholder="Masukkan Nama Akun" required="">
                    </div>
                    <div class="mb-2 kasBank-group">
                        <label for="induk" class="control-label">Detail Dari</label>
                        <select name="induk" id="induk" class="form-control">
                            <option value="">Pilih Akun</option>

                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="saveBtn">Simpan</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
