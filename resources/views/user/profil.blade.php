@extends("layouts.app")

@section("content")
<div class="row">
    <div class="col-12">
        <div class="page-title-box">

            <h4 class="page-title">Akun Saya</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if(session()->has('status'))
                <div class="alert alert-{{session('status')}}" role="alert">
                    <strong>{{strtoupper(session('status'))}} - </strong> {{session('message')}}
                </div>
                @endif
                {{-- <h4 class="header-title with-border">My Profil</h4> --}}
                <form method="POST" action="/update-profil" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="" style="text-align: center; padding: 30px 20px">
                            <a href="javascript: void(0);">
                                <img src="{{asset('userFoto/'.$user->foto)}}" alt="user-image" height="150"
                                    class="rounded-circle shadow-sm">
                            </a>
                        </div>
                        <input type="hidden" required name="id_pengguna" id="id_pengguna" value="{{enc($user->id)}}">
    
                        <div class="mb-2">
                            <label for="nama_lengkap" class="control-label">Nama Lengkap <span
                                    style="color: red;">*</span></label>
    
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="{{$user->name}}" required="">
    
                        </div>
                       
                        <div class="mb-2">
                            <label for="email" class="control-label">Alamat Email <span
                                    style="color: red;">*</span></label>
    
                            <input type="email" class="form-control" id="email" name="email" value="{{$user->email}}"
                                placeholder="youremail@mail.com" required="">
    
                        </div> 
                        <div class="mb-2">
                            <label for="username" class="control-label">Username</label>
    
                            <input type="text" class="form-control" id="username" name="username" value="{{$user->username}}">
    
                        </div>
                        <div class="mb-2">
                            <label for="level" class="control-label">Level Pengguna </label>
                            <input type="text" value="{{$user->level}}" readonly class="form-control">
                        </div>
                        <div class="mb-2">
                            <label for="file" class="control-label">Upload Foto Profil </label>
                            <input type="file" id="file" name="file" class="form-control">
                            <div class="mb-2">
                                <img id="preview-image-before-upload" alt="Preview Image"
                                    style="max-height: 100px; max-width:350px">
                            </div>
                        </div>
                        <br>
                        <div>
                            <p>Kosongkan jika tidak ingin merubah Password</p>
                        </div>
                        <div class="mb-2">
                            <label for="password" class="control-label">Password</label>
    
                            <input type="password" autocomplete="off" value="" name="password" id="password" class="form-control">
    
                        </div>
                        <div class="mb-2">
                            <label for="repassword" class="control-label">Ulangi Password</label>
    
                            <input type="password" autocomplete="off" value="" name="repassword" id="repassword" class="form-control">
    
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="saveBtn">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(e) {

        $('#preview-image-before-upload').attr('src', '/userFoto/{{$user->foto}}');
$('#file').change(function() {

    let reader = new FileReader();

    reader.onload = (e) => {

        $('#preview-image-before-upload').attr('src', e.target.result);
    }

    reader.readAsDataURL(this.files[0]);

});

});
</script>
@endsection