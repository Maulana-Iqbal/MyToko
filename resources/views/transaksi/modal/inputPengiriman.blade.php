<div class="row">
    <div class="col-12 col-lg-6">
        <div class="mb-2">
            <label for="resi">No. Resi Pengiriman</label>
            <input type="text" name="resi" id="resi" @if (isset($shipping->resi))
            value="{{$shipping->resi}}"
            @endif class="form-control">
        </div>
        <div class="mb-2">
            <label for="kurir">Jasa Pengiriman</label>
            <input type="text" name="kurir" id="kurir" @if (isset($shipping->kurir))
            value="{{$shipping->kurir}}"
            @endif class="form-control">
        </div>
    </div>
    <div class="col-12 col-lg-6">
        <div class="mb-2">
            <label for="biayaPengiriman">Biaya Pengiriman</label>
            <input type="text" name="biayaPengiriman" id="biayaPengiriman" @if (isset($shipping->biaya))
            value="{{$shipping->biaya}}"
            @endif class="form-control rupiah">
        </div>
        <div class="mb-2">
            <label for="file">Upload Resi</label>
            <input type="file" name="file" id="file" class="form-control">
        </div>
        <div class="mb-2">
            <img id="preview-image-before-upload" alt="Preview Image" style="max-height: 100px; max-width:350px">
        </div>
    </div>
</div>