@include('auth.authHeader')
<div class="text-center w-75 m-auto">
    <h4 class="text-dark-50 text-center mt-0 fw-bold">Ubah Password</h4>
    <p class="text-muted mb-4">Masukkan Alamat Email terdaftar dan kami akan mengirimkan Email Instruksi untuk merubah Password</p>
</div>
@if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <div class="row mb-3">
        <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Alamat Email') }}</label>

        <div class="col-md-8">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                value="{{ old('email') }}" required autocomplete="email" autofocus>

            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="row mb-0">
        <div class="col-md-8 offset-md-4">
            <button type="submit" class="btn btn-primary">
                {{ __('Kirim') }}
            </button>
            <a href="/login" class="btn btn-danger">Batal</a>
        </div>
    </div>
</form>
@include('auth.authFooter')
