@extends('layout.auth')
@section('content')
    <div class="content-body">
        <div class="auth-wrapper auth-v1 px-2">
            <div class="auth-inner py-2">
                <!-- Forgot Password v1 -->
                <div class="card mb-0">
                    <div class="card-body">
                        <a href="javascript:void(0);" class="brand-logo">
                            <svg viewbox="0 20 180 180" version="1.1" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" height="34">
                            <g id="Page-1" stroke="none" stroke-width="1" fill="#766bf0"
                                fill-rule="evenodd">
                                <g id="Artboard"
                                    transform="translate(0.000000,200.000000) scale(0.100000,-0.100000)">
                                    <path class="text-primary" id="Path" d="M838 1800 c-306 -52 -554 -276 -629 -569 -98 -382 88 -782 448 -964
                    105 -54 217 -79 348 -80 66 -1 147 5 180 12 191 42 370 161 473 315 111 167
                    163 384 133 556 l-8 45 -2 -43 c-2 -69 -31 -181 -67 -260 -115 -251 -336 -414
                    -611 -452 -135 -18 -289 11 -402 77 -53 30 -150 105 -151 116 0 4 28 7 63 7
                    l62 1 75 172 75 172 87 3 87 3 -38 -88 c-21 -48 -57 -127 -79 -175 l-41 -88
                    148 0 147 0 76 173 c42 94 100 224 129 287 129 287 123 337 -47 373 -79 17
                    -236 37 -284 37 -18 -1 -10 -7 30 -23 63 -25 100 -71 100 -124 0 -36 -35 -149
                    -52 -170 -10 -12 -168 -20 -168 -8 0 2 24 60 54 127 39 86 51 125 43 130 -26
                    14 -136 37 -195 40 l-62 3 -156 -345 c-85 -190 -159 -349 -164 -353 -13 -14
                    -68 142 -80 230 -18 135 9 291 72 418 120 238 333 392 591 427 l92 13 -50 7
                    c-66 8 -169 8 -227 -2z" />
                                    <path id="Path1" d="M1560 1408 c0 -125 3 -145 29 -225 50 -153 49 -284 -4 -436 -13 -39
                    -23 -72 -21 -74 5 -5 48 54 79 107 14 25 38 81 53 125 24 69 28 96 28 200 1
                    99 -3 133 -22 195 -26 83 -65 157 -111 210 l-30 35 -1 -137z" />
                                </g>
                            </g>

                        </svg>
                            <h2 class="brand-text text-primary ml-1">HBOS</h2>
                        </a>

                        <h4 class="card-title mb-1">Lupa Password? 🔒</h4>
                        <p class="card-text mb-2">Masukan E-mail kamu, kami akan mengirimkan link untuk reset
                            password ke email kamu</p>

                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                Kami telah mengirimkan link reset password ke email kamu
                            </div>
                        @endif

                        <form method="POST" id="form-action" action="{{ route('password.email') }}" class="auth-forgot-password-form mt-2"
                            action="page-auth-reset-password-v1.html" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="forgot-password-email" class="form-label">Email</label>
                                <input type="text" class="form-control @error('email') is-invalid @enderror"
                                    id="forgot-password-email" name="email" placeholder="email@example.com"
                                    value="{{ old('email') }}" aria-describedby="forgot-password-email" tabindex="1"
                                    autofocus />
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary btn-block" tabindex="2">Kirim reset link</button>
                        </form>

                        <p class="text-center mt-2">
                            <a href="{{ route('login') }}"> <i data-feather="chevron-left"></i> Kembali ke
                                halaman login </a>
                        </p>
                    </div>
                </div>
                <!-- /Forgot Password v1 -->
            </div>
        </div>

    </div>
@endsection
@push('js')
<script>

    $('#form-action').on('submit', function(e){
        $(this).find('button').attr('disabled', 'disabled')
                .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="ml-25 align-middle"> Memproses data...</span>')
    })

</script>
@endpush
