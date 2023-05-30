<div id="ajaxModel" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog"
    aria-labelledby="success-header-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-info">
                <h4 class="modal-title" id="success-header-modalLabel">Kategori Akun</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form id="kategoriAkunForm" name="kategoriAkunForm" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id_kategoriAkun" id="id_kategoriAkun">
                    <div class="mb-2 kategoriAkun-group">
                        <label for="kode" class="col-sm-3 control-label">Kode Kategori Akun <span
                                style="color: red;">*</span></label>
                        <input type="text" class="form-control" id="kode" name="kode"
                            placeholder="Masukkan Kode Kategori Akun" required="">
                    </div>
                    <div class="mb-2 kategoriAkun-group">
                        <label for="name" class="col-sm-3 control-label">Nama Kategori Akun <span
                                style="color: red;">*</span></label>
                        <input type="text" class="form-control" id="name" name="name"
                            placeholder="Masukkan Nama Kategori Akun" required="">
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
