@include('auth.authHeader')
@if (session('resent'))
    <div class="alert alert-success" role="alert">
        {{ __('Link Verifikasi Baru telah kami kirimkan ke Email Kamu') }}
    </div>
@endif

{{ __('Sebelum melanjutkan, Silahkan cek Email kamu untuk mendapatkan Link Verifikasi.') }}
{{ __('Jika kamu tidak mendapatkan email') }},
<form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
    @csrf
    <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('Klik Disini untuk Mendapatkan Email Baru') }}</button>.
</form>
@include('auth.authFooter')
