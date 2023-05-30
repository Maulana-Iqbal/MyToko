 
<!DOCTYPE html>
<html  lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <title>{{  website()->nama_website }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('fe_assets/img/logo_fix.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App css -->
    <link href="{{ asset('hy_assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('hy_assets/css/app-modern.min.css') }}" rel="stylesheet" type="text/css" id="light-style" />
    <link href="{{ asset('hy_assets/css/app-modern-dark.min.css') }}" rel="stylesheet" type="text/css" id="dark-style" />
</head>
<body class="authentication-bg pb-0" data-layout-config='{"darkMode":false}'>
 <div class="auth-fluid">

<!--Auth fluid left content -->
<div class="auth-fluid-form-box">
    <div class="align-items-center d-flex h-100">
        <div class="card-body">

            <!-- Logo -->
            <div class="auth-brand text-center text-lg-start">
                <a href="index.html" class="logo-dark">
                    <span><img src="assets/images/logo-dark.png" alt="" height="18"></span>
                </a>
                <a href="index.html" class="logo-light">
                    <span><img src="assets/images/logo.png" alt="" height="18"></span>
                </a>
            </div>

            <!-- title-->
            <h4 class="mt-0">Pendaftaran Akun</h4>
            <p class="text-muted mb-4">Belum punya akun? Buat akun kamu hanya dalam beberapa menit.</p>

            <!-- form -->
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="mb-3">
                    <label for="fullname" class="form-label">Nama Lengkap</label>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="emailaddress" class="form-label">Alamat Email Aktif</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password-confirm" class="form-label">{{ __('Ulangi Password') }}</label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                </div>
              <!--   <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="checkbox-signup">
                        <label class="form-check-label" for="checkbox-signup">Saya Setuju <a href="javascript: void(0);" class="text-muted">Terms and Conditions</a></label>
                    </div>
                </div> -->
                <div class="mb-0 d-grid text-center">
                    <button class="btn btn-primary" type="submit"><i class="mdi mdi-account-circle"></i> {{ __('Daftar Sekarang') }} </button>
                </div>

            </form>
            <!-- end form-->

            <!-- Footer-->
            <footer class="footer footer-alt">
                <p class="text-muted">Sudah punya akun? <a href="/login" class="text-muted ms-1"><b>Masuk Disini</b></a></p>
            </footer>

        </div> <!-- end .card-body -->
    </div> <!-- end .align-items-center.d-flex.h-100-->
</div>
<!-- end auth-fluid-form-box-->
    
    <!-- Auth fluid right content -->
    <div class="auth-fluid-right text-center">
        <div class="auth-user-testimonial">
            <h2 class="mb-3">I love the color!</h2>
            <p class="lead"><i class="mdi mdi-format-quote-open"></i> It's a elegent templete. I love it very much! . <i class="mdi mdi-format-quote-close"></i>
            </p>
            <p>
                - Hyper Admin User
            </p>
        </div> <!-- end auth-user-testimonial-->
    </div>
    <!-- end Auth fluid right content -->
</div>
<!-- bundle -->
<script src="{{ asset('hy_assets/js/vendor.min.js') }}"></script>
<script src="{{ asset('hy_assets/js/app.min.js') }}"></script>

</body>
</html>
