<!-- Success Header Modal -->

<div id="ajaxModel" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="success-header-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-info">
                <h4 class="modal-title" id="success-header-modalLabel">Barang</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form id="produkForm" name="produkForm" action="javascript:void(0);" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id_produk" id="id_produk">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-2 produk-group">
                                <label for="kode_produk" class="control-label">Kode Barang <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" id="kode_produk" name="kode_produk" required="">
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="mb-2 produk-group">
                                <label for="merek" class="control-label">Merek</label>
                                <input type="text" class="form-control" id="merek" name="merek" placeholder="Merek Barang">
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="mb-2 produk-group">
                                <label for="nama_produk" class="control-label">Nama Barang <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" id="nama_produk" name="nama_produk" placeholder="Nama Barang" required="">
                            </div>
                            <div class="mb-2 produk-group">
                                <label for="slug" class="control-label">Slug Barang <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" id="slug" name="slug" placeholder="Slug Barang" required="">
                            </div>
                        </div>
                        <div class="col-lg-6">

                            <div class="mb-2 produk-group">
                                <label for="kategori" class="control-label">Kategori <span style="color: red;">*</span></label>
                                <a target="_blank" href="/kategori"><small class="text-danger float-end">Buat Kategori Baru</small></a>
                                <!-- Single Select -->
                                <select id="kategori" name="kategori" class="form-control">
                                    <option value="">Pilih Kategori</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-2 produk-group">
                                <label for="satuan" class="control-label">Satuan <span style="color: red;">*</span></label>
                                <a target="_blank" href="/satuan"><small class="text-danger float-end">Buat Satuan Baru</small></a>
                                <!-- Single Select -->
                                <select id="satuan" name="satuan" class="form-control">
                                    <option value="">Pilih Satuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="mb-2 produk-group">
                                <label for="keterangan" class="control-label">Keterangan <span style="color: red;">*</span></label>
                                <textarea name="keterangan" id="keterangan" maxlength="350" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-2 produk-group">
                                <label for="harga" class="control-label">Harga Modal</label>
                                <input type="text" class="form-control rupiah" id="harga" name="harga" value="0" placeholder="Rp. xxxxxxx" required="">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-2 produk-group">
                                <label for="berat" class="control-label">Berat (Gram) <span style="color: red;">*</span></label>
                                <input type="number" class="form-control nilai mb-2" id="berat" name="berat" value="0" min="1" required="">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-2 produk-group inputHargaJual">
                                <label for="harga_jual" class="control-label">Harga Jual</label>

                                <input type="text" class="form-control rupiah" id="harga_jual" value="0" name="harga_jual" placeholder="Rp. xxxxxxx" required="">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="mb-2 produk-group">
                                <label for="min" class="control-label">Min. Order</label>

                                <input type="number" class="form-control nilai mb-2" id="min" name="min" value="0" required="">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-2 produk-group inputHargaGrosir">
                                <label for="harga_grosir" class="control-label">Harga Grosir</label>
                                <input type="text" class="form-control rupiah" id="harga_grosir" value="0" name="harga_grosir" placeholder="Rp. xxxxxxx" required="">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-2 produk-group">
                                <label for="max" class="control-label">Max. Order</label>
                                <input type="number" class="form-control nilai mb-2" id="max" name="max" value="0" required="">
                            </div>
                        </div>
                        <div class="col-lg-6 d-none">
                            <div class="mb-2 produk-group">
                                <label for="min_stock" class="control-label">Minimal Stock</label>
                                <input type="number" class="form-control nilai mb-2" id="min_stock" name="min_stock" value="0" required="">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-2 produk-group">
                                <label for="file" class="control-label">Pilih Gambar Barang<span class="edt1" style="color: red;">*</span></label>
                                <input type="file" class="form-control" id="file" name="file">
                                <small class="edt2" style="display: none;">Kosongkan jika ingin menggunakan
                                    Gambar
                                    sebelumnya</small>

                                <small>Hanya diperbolehkan dengan extensi : <b style="color: darkred;"> .jpg / .jpeg
                                        /
                                        PNG</b>
                                    | Maksimal Ukuran 1MB</small>
                                <div class="mb-2">
                                    <img id="preview-image-before-upload" alt="Preview Image" style="max-height: 100px;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="mb-2 produk-group">
                                <label for="deskripsi" class="control-label">Detail Barang <span style="color: red;">*</span></label>

                                <textarea name="deskripsi" id="deskripsi" class="form-control"></textarea>

                            </div>
                        </div>
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