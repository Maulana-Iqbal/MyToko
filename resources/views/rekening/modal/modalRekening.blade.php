<div id="ajaxModel" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog"
        aria-labelledby="success-header-modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-info">
                    <h4 class="modal-title" id="success-header-modalLabel">Rekening</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form id="rekeningForm" name="rekeningForm" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id_rekening" id="id_rekening">
                        <div class="mb-2">
                            <label for="akun">Pilih Akun</label>
                            <select name="akun" id="akun" class="form-control akun select2">
                            <option value="">Pilih Akun</option>
                            </select>
                        </div>
                        <div class="mb-2 rekening-group">
                            <label for="nama_bank" class="col-sm-3 control-label">Nama Bank <span
                                    style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="nama_bank" name="nama_bank"
                                placeholder="Masukkan Nama Bank" required="">
                        </div>
                        <div class="mb-2 rekening-group">
                            <label for="nama_rek" class="col-sm-3 control-label">Nama Rekening <span
                                    style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="nama_rek" name="nama_rek"
                                placeholder="Masukkan Nama Rekening" required="">
                        </div>
                        <div class="mb-2 rekening-group">
                            <label for="no_rek" class="col-sm-3 control-label">No Rekening <span
                                    style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="no_rek" name="no_rek"
                                placeholder="Masukkan No Rekening" required="">
                        </div>
                        <div class="mb-2 rekening-group">
                            <label for="jenis_rek" class="col-sm-3 control-label">Jenis Rekening <span
                                    style="color: red;">*</span></label>
                                    <select name="jenis_rek" id="jenis_rek" class="form-control">
                                        <option value="">Pilih Jenis Rekening</option>
                                        <option value="2">Rekening Bank</option>
                                        <option value="3">Virtual Akun</option>
                                    </select>
                        </div>
                        <div class="mb-2 rekening-group">
                            <label for="isActive" class="col-sm-3 control-label">Status <span
                                    style="color: red;">*</span></label>
                                    <select name="isActive" id="isActive" class="form-control">
                                        <option value="">Pilih Status</option>
                                        <option value="1">Aktif</option>
                                        <option value="0">Tidak Aktif</option>
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
