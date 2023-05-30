@include('auth.authHeader')


                                <div class="text-center w-75 m-auto">
                                    <h4 class="text-dark-50 text-center mt-0 fw-bold">Masuk Akun</h4>
                                    <p class="text-muted mb-4">Masukkan Email dan Password untuk masuk ke Dashboard.</p>
                                </div>

                                <form action="{{ route('login') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="emailaddress" class="form-label">Alamat Email</label>
                                        <input class="form-control @error('email') is-invalid @enderror"  name="email" type="email" id="emailaddress"  value="superadmin@mail.com" required autocomplete="email" autofocus>
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <a href="/forgot-password" class="text-muted float-end"><small>Lupa Password? Klik Disini.</small></a>
                                        <label for="password" class="form-label">Password</label>
                                        <div class="input-group input-group-merge">
                                            <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="12345678" required autocomplete="current-password">
                                            <div class="input-group-text" data-password="false">
                                                <span class="password-eye"></span>
                                            </div>
                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3 mb-0 text-center">
                                        <button class="btn btn-primary" type="submit"> Masuk </button>  <a href="/auth/google">
                    <img src="https://developers.google.com/identity/images/btn_google_signin_dark_normal_web.png" style="margin-left: 3em;">
                </a>
                                    </div>

                                </form>

@include('auth.authFooter')
