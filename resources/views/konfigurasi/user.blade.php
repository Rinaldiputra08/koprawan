@extends ('layout.master')
@push('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@push('css')
    {{-- <link rel="stylesheet" type="text/css" href="{{asset('')}}assets/css/plugins/extensions/ext-component-toastr.css"> --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/vendors/css/forms/select/select2.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('') }}assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('') }}assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/css/plugins/forms/form-validation.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/vendors/css/extensions/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/vendors/css/croppie/croppie.css">
@endpush
@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h4 class="card-title">User</h4>
                    </div><br />
                    <div class="col-md-4">
                        @can('create '.request()->segment(1).'/'.request()->segment(2))
                            <button type="button" class="btn btn-gradient-primary add">Tambah</button>
                        @endcan
                    </div>
                    <div class="card-body">
                        {!! $dataTable->table() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('vendor')
    <script src="{{ asset('') }}assets/vendors/js/tables/datatable/jquery.dataTables.min.js"></script>
    <script src="{{ asset('') }}assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js"></script>
    <script src="{{ asset('') }}assets/vendors/js/tables/datatable/datatables.responsive.min.js"></script>
    <script src="{{ asset('') }}assets/vendors/js/forms/validation/jquery.validate.min.js"></script>
    <script src="{{ asset('') }}assets/js/scripts/forms/form-validation.js"></script>
    <script src="{{ asset('') }}assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
    <script src="{{ asset('') }}assets/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="{{ asset('') }}assets/vendors/js/croppie/croppie.js"></script>
@endpush
@push('js')

    {!! $dataTable->scripts() !!}
    <script>
        'use strict'

        var userJs = function() {

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

            function callSelect2(){
                const selectTwo = $('.select2').select2()   
            }

            $('#user-table').on('click', '.edit', function() {
                let id = $(this).data('id');
                showLoading();
                $.ajax({
                    method: "GET",
                    url: `{{ url('konfigurasi/users') }}/${id}/edit`,
                    success: function(result) {
                        callModal(result)
                        hideLoading();
                        // readFoto();
                        storeValidation();
                        callSelect2();
                        _loadFeather()
                        datePicker()
                        changePhoto()
                    }
                })
            })

            $('#user-table').on('click', '.trash', function() {
                let id = $(this).data('id');
                confirmation(function() {
                    showLoading();
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        method: 'DELETE',
                        url: `{{ url('konfigurasi/users') }}/${id}`,
                        success: function(m) {
                            hideLoading();
                            if (m.status == 'success') {
                                window.LaravelDataTables['menu-table'].ajax.reload();
                            }
                        }
                    })
                })
            })

            $('.add').on('click', function() {
                showLoading();
                $.ajax({
                    method: "GET",
                    url: "{{ route('users.create') }}",
                    success: function(result) {
                        callModal(result);
                        // readFoto();
                        hideLoading();
                        storeValidation();
                        callSelect2()
                        _loadFeather()
                        datePicker()
                        changePhoto()
                    }
                })
            })

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

            function storeValidation() {
                const form = $('#form-user')
                form.validate({
                    submitHandler: function(e) {
                        let formdata = new FormData(e)
                        // console.log(formdata);
                        let url, method
                        let id = $(`input[name='id']`).val()

                        if (id === "") {
                            url = "{{ route('users.store') }}";
                            method = 'POST'
                        } else {
                            url = `{{ url('konfigurasi/users') }}/${id}`;
                            method = 'POST'
                        }
                        // console.log(formdata);
                        showLoading();
                        saveLoading()
                        $.ajax({
                            headers: {
                                'Accept': 'application/json, text-plain, */*',
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            method,
                            url,
                            data: formdata,
                            processData: false,
                            contentType: false,
                            success: function(result) {
                                saveLoading('hide', 'Simpan');
                                hideLoading();
                                // console.log(result);
                                if (result.status == 'success') {
                                    $('.modal-global').modal('hide');
                                    window.LaravelDataTables['user-table'].ajax.reload();
                                }
                                toastr[result.status](result.message, {
                                    closeButton: true,
                                    tapToDismiss: false
                                });
                            },
                            error: function(e) {
                                saveLoading('', 'Simpan');
                                hideLoading();
                                const errors = e.responseJSON?.errors;
                                if (errors) {
                                    for (const [key, value] of Object.entries(errors)) {
                                        $(`input[name='${key}']`).parent().append(
                                            `<span class="error">${value}</span>`)
                                    }
                                }
                            }
                        })
                    }
                });
            }
        }()

    </script>
@endpush
