<div id="ajaxModel" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog"
    aria-labelledby="success-header-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-info">
                <h4 class="modal-title" id="success-header-modalLabel">Kas & Bank</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form id="kasBankForm" name="kasBankForm" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id_kasBank" id="id_kasBank">
                    {{-- <div class="mb-2 kasBank-group">
                        <label for="tipe" class="control-label">Tipe <span
                                style="color: red;">*</span></label>
                        <select name="tipe" id="tipe" class="form-control">
                            <option value="header">Header</option>
                            <option value="detail">Detail</option>
                           </select>
                    </div> --}}
                    <div class="mb-2 kasBank-group">
                        <label for="akun" class="control-label">Akun <span
                                style="color: red;">*</span></label>
                        <select name="akun" id="akun" class="form-control">
                            <option value="">Akun</option>

                        </select>
                    </div>
                    {{-- <div class="mb-2 kasBank-group">
                        <label for="induk" class="control-label">Detail Dari</label>
                        <select name="induk" id="induk" class="form-control">
                            <option value="">Pilih</option>

                        </select>
                    </div> --}}

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="saveBtn">Simpan</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
