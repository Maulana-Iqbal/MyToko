 <!-- Modal -->
 <div class="modal fade" id="gambarModal" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"
 aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
 <div class="modal-dialog modal-lg modal-dialog-centered">
     <div class="modal-content">
         <div class="modal-header bg-info">
             <h5 class="modal-title" id="exampleModalToggleLabel2">Gambar Barang Multi Upload</h5>
             <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
             <form id="gambarForm" enctype="multipart/form-data">
                 @csrf
                 <input type="hidden" id="dataId" name="dataId">
                 <div class="form-group">
                     <small>Hanya diperbolehkan dengan extensi : <b style="color: darkred;"> .jpg / .jpeg
                             /
                             PNG</b>
                         | Maksimal Ukuran 1MB / 1 Gambar Barang</small>
                     <input type="file" id="file" name="file[]" multiple class="form-control" required
                         accept="image/*">
                     <code>Setiap Barang Hanya Boleh 3 Gambar Tambahan</code>
                     @if ($errors->has('files'))
                         @foreach ($errors->get('files') as $error)
                             <span class="invalid-feedback" role="alert">
                                 <strong>{{ $error }}</strong>
                             </span>
                         @endforeach
                     @endif
                 </div>

                 <div class="form-group float-end">
                     <button type="button" class="btn btn-success mt-2" id="saveGambar">Upload</button>
                     <button type="reset" data-bs-target="#gambarModal" data-bs-toggle="modal"
                         data-bs-dismiss="modal" class="btn btn-danger mt-2">Batal</button>
                 </div>
             </form>

             <h3 style="margin-top:70px;">Gambar Barang</h3>
             <div class="row text-center text-lg-start list-gambar-produk">
             </div>
         </div>

     </div><!-- /.modal-content -->
 </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
