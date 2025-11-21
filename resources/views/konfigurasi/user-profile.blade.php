@extends ('layout.master')
@push('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/vendors/css/croppie/croppie.css">
@endpush
@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h4 class="card-title">Profile</h4>
                    </div>
                    <div class="card-body mt-3">
                        @if (session()->has('message'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <div class="alert-body">
                                    {{ session('message') }}
                                </div>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                        @endif
                        <form id="form-user" enctype="multipart/form-data" method="POST"
                            action="{{ route('users.update', $getDetail->id) }}">
                            @csrf
                            @if ($getDetail->id)
                                @method('put')
                            @endif
                            <input type="hidden" name="id" value="{{ $getDetail->id }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input type="text" class="form-control" id="username" name="username"
                                            placeholder="Username" value="{{ $getDetail->username }}"
                                            {{ $getDetail->username ? 'readonly' : '' }}>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Nama</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="Nama User" value="{{ $getDetail->name }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            placeholder="Email" value="{{ $getDetail->email }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="text" class="form-control" id="password" name="password">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password_confirmation">Password Konfirmasi</label>
                                        <input type="text" class="form-control" id="password_confirmation"
                                            name="password_confirmation">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="no_telp">No telepon</label>
                                        <input type="text" class="form-control" name="no_telp" id="no_telp"
                                            value="{{ $getDetail->no_telp }}" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="tgl_lahir">Tanggal lahir</label>
                                    <div class="input-group mb-2">
                                        <input type="text" id="tgl_lahir"
                                            class="form-control flatpickr-basic tanggal flatpickr-input active"
                                            name="tgl_lahir" value="{{ convertDate($getDetail->tgl_lahir) }}"
                                            data-date-format="d-m-Y" placeholder="Pilih tanggal" readonly="readonly">
                                        {{-- <input type="text" class="form-control flatpickr-basic tanggal flatpickr-input active" value="{{ $data->waktu_keluar ? convertDate(explode(' ',$data->waktu_keluar)[0]) : '' }}" name="tanggal_keluar" data-date-format="d-m-Y" placeholder="Pilih tanggal" readonly="readonly"> --}}
                                        <div class="input-group-append">
                                            <span class="input-group-text cursor-pointer"><i
                                                    data-feather="calendar"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="alamat">Alamat</label>
                                        <textarea name="alamat" id="alamat" class="form-control">{{ $getDetail->alamat }}</textarea>
                                    </div>
                                </div>
                                {{-- <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Foto</label>
                                        <div class="media mb-2">
                                            <img src="{{ asset('') }}{{ $getDetail->foto ? "storage/images/profile/small_$getDetail->foto" : 'assets/images/avatars/1.png' }}"
                                                alt="users avatar"
                                                class="user-avatar users-avatar-shadow rounded mr-2 my-25 cursor-pointer"
                                                height="120" width="120" />
                                            <div class="media-body mt-50">
                                                <div class="col-12 d-flex mt-1 px-0">
                                                    <label class="btn btn-primary mr-75 mb-0" for="change-picture">
                                                        <span class="d-none d-sm-block">Change</span>
                                                        <input class="form-control" type="file" id="change-picture"
                                                            name="foto" hidden accept="image/png, image/jpeg, image/jpg" />
                                                        <span class="d-block d-sm-none">
                                                            <i class="mr-0" data-feather="edit"></i>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="media mb-2 avatar-group">
                                            <img src="{{ asset('') }}{{ $getDetail->foto ? "storage/images/profile/small_$getDetail->foto" : 'assets/images/avatars/1.png' }}"
                                                alt="delivery avatar"
                                                class="delivery-avatar users-avatar-shadow rounded mr-2 my-25 cursor-pointer"
                                                height="200" />
                                        </div>
                                        <div class="media mb-2 croppie-group d-none">
                                            <div class="picture-canvas" style="width: 300px; height: auto;">
                                            </div>
                                            <div class="media-body mt-50 ml-4">
                                                <div class="col-12 d-flex mt-1 px-0">
                                                    <button type="button" class="btn btn-primary rotate" data-deg="90">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                            fill="currentColor" class="bi bi-arrow-counterclockwise"
                                                            viewBox="0 0 16 16">
                                                            <path fill-rule="evenodd"
                                                                d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z" />
                                                            <path
                                                                d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z" />
                                                        </svg>
                                                    </button>
                                                </div>
                                                <div class="col-12 d-flex mt-1 px-0">
                                                    <button type="button" class="btn btn-primary rotate" data-deg="-90">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                            fill="currentColor" class="bi bi-arrow-clockwise"
                                                            viewBox="0 0 16 16">
                                                            <path fill-rule="evenodd"
                                                                d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z" />
                                                            <path
                                                                d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 browse-picture">
                                    <div class="form-group d-flex">
                                        <label class="btn btn-primary mr-75 mb-0" for="change-picture">
                                            <span class="label-button">Pilih Foto</span>
                                            <input class="form-control" type="file" id="change-picture" name="foto" hidden
                                                accept="image/jpeg, image/jpg" />
                                        </label>
                                        <div class="crop-picture d-none">
                                            <button class="btn btn-success crop" type="button">Crop Foto</button>
                                        </div>
                                        <input type="hidden" name="foto_upload" readonly>
                                    </div>
                                </div>

                            </div>
                            <button type="submit" class="btn btn-primary btn-save">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('vendor')
    <script src="{{ asset('') }}assets/vendors/js/croppie/croppie.js"></script>
@endpush
@push('js')
    <script>
        'use strict'

        var userJs = function() {

            changePhoto()

            $('#form-user').on('submit', function(e){
                saveLoading()
            })

            function changePhoto() {
                var croppicture = $('.picture-canvas').croppie({
                    enableExif: true,
                    viewport: {
                        width: 200,
                        height: 200,
                        type: 'square'
                    },
                    enableOrientation: true,
                    boundary: {
                        width: 300,
                        height: 300
                    }
                })

                $('#change-picture').on('change', function(e) {
                    let picturename = $(this).val(),
                        file_extension = picturename.split('.').pop(),
                        allowed_extension = ['jpg', 'jpeg', 'JPG', 'JPEG']

                    if (picturename == '') return false;

                    if (!allowed_extension.includes(file_extension)) {
                        e.preventDefault()
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops..',
                            text: 'Format file pilih salah, pilih gambar dengan ekstensi jpg atau jpeg'
                        })
                        return
                    }

                    $('.avatar-group').addClass('d-none')
                    $('.croppie-group').removeClass('d-none')
                    $('.label-button').text('Ganti')
                    $('.crop-picture').removeClass('d-none')

                    let reader = new FileReader()

                    reader.onload = function(event) {
                        croppicture.croppie('bind', {
                            url: event.target.result
                        })
                    }
                    reader.readAsDataURL(this.files[0])
                })

                $('.rotate').on('click', function() {
                    croppicture.croppie('rotate', parseInt($(this).data('deg')))
                })

                $('.crop').on('click', function() {
                    croppicture.croppie('result', {
                        type: 'canvas',
                        format: 'jpeg',
                        size: {
                            width: 200,
                            height: 200,
                            type: 'square'
                        }
                    }).then(function(res) {
                        $('.delivery-avatar').attr('src', res)
                        $('[name="foto_upload"]').val(res)

                        $('.avatar-group').removeClass('d-none')
                        $('.croppie-group').addClass('d-none')
                        $('.crop-picture').addClass('d-none')
                        $('.error').remove()
                    })
                })
            }

            // readFoto()

            function readFoto() {
                var changePicture = $('#change-picture'),
                    userAvatar = $('.user-avatar');
                if (changePicture.length) {
                    $(changePicture).on('change', function(e) {
                        var reader = new FileReader(),
                            files = e.target.files;
                        reader.onload = function() {
                            if (userAvatar.length) {
                                userAvatar.attr('src', reader.result);
                            }
                        };
                        reader.readAsDataURL(files[0]);
                    });
                }
            }

        }()
    </script>
@endpush
