@extends('layout.auth')
@section('content')
    <div class="content-body">
        <div class="auth-wrapper auth-v1 px-2">
            <div class="auth-inner py-2">
                <!-- Login v1 -->
                <div class="card mb-0">
                    <div class="card-body">
                        <a href="javascript:void(0);" class="brand-logo d-flex align-items-center">
                            <img src="{{ asset('assets/images/logo/koprawan.png') }}" alt="logo" height="45" class="mr-25">
                            {{-- <img src="{{ asset('assets/images/logo/koprawan.png') }}" alt="logo" height="45"
                                class="mr-1"> --}}
                            <h2 class="brand-text text-primary mb-0 ml-1">KOPRAWAN</h2>
                        </a>


                        <h1 class="card-title mb-1 text-center"><b>Koprasi Karyawan</b></h1>
                        <p class="card-text mb-2 text-center">Operational System</p>

                        <form class="auth-login-form mt-2" action="{{ route('post.login') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label class="form-label" for="login-email">Username</label>
                                <input class="form-control @error('username') is-invalid @enderror" type="text"
                                    name="username" placeholder="Username" value="{{ old('username') }}" autofocus=""
                                    tabindex="1" />
                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="d-flex justify-content-between">
                                    <label for="login-password">Password</label>
                                    <a href="{{ route('password.request') }}"><small>Lupa Password?</small></a>
                                </div>
                                <div class="input-group input-group-merge form-password-toggle">
                                    <input class="form-control form-control-merge @error('password') is-invalid @enderror"
                                        id="login-password" type="password" name="password" placeholder="············"
                                        aria-describedby="login-password" tabindex="2" />
                                    <div class="input-group-append"><span class="input-group-text cursor-pointer"><i
                                                data-feather="eye"></i></span></div>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                            </div>
                            {{-- <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" id="remember-me" type="checkbox" tabindex="3" />
                                    <label class="custom-control-label" for="remember-me"> Remember Me</label>
                                </div>
                            </div> --}}
                            <button class="btn btn-primary btn-block" tabindex="4">Login</button>
                        </form>

                        <div class="divider my-2">
                            <div class="divider-text">atau login menggunakan</div>
                        </div>
                        @error('login_social')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <div class="auth-footer-btn d-flex justify-content-center">
                            <a href="{{ route('auth.google') }}"
                                class="btn btn-google waves-effect waves-float waves-light">
                                Google <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-mail">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                    </path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                            </a>

                        </div>
                        <p class="text-center mt-2">
                            <span>© 1997-{{ date('Y') }} Copyright <a class="ml-25" href="https://honda-bintaro.com"
                                    target="_blank">KOPRAWAN</a>.
                                <br />All rights
                                reserved.</span>
                        </p>
                    </div>
                </div>
                <!-- /Login v1 -->
            </div>
        </div>

    </div>
@endsection