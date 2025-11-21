@extends ('layout.master')
@push('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@push('css')
    {{-- <link rel="stylesheet" type="text/css" href="{{asset('')}}assets/css/plugins/extensions/ext-component-toastr.css"> --}}
    <link rel="stylesheet" type="text/css"
        href="{{ asset('') }}assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('') }}assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/css/plugins/forms/form-validation.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/vendors/css/extensions/sweetalert2.min.css">
@endpush
@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h4 class="card-title">Menu</h4>
                    </div><br />
                    <div class="col-md-4">
                        @can('create ' . request()->segment(1) . '/' . request()->segment(2))
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
    {{-- {!! $dataTable->scripts() !!} --}}
@endsection
@push('vendor')
    <script src="{{ asset('') }}assets/vendors/js/tables/datatable/jquery.dataTables.min.js"></script>
    <script src="{{ asset('') }}assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js"></script>
    <script src="{{ asset('') }}assets/vendors/js/tables/datatable/datatables.responsive.min.js"></script>
    <script src="{{ asset('') }}assets/vendors/js/forms/validation/jquery.validate.min.js"></script>
    <script src="{{ asset('') }}assets/js/scripts/forms/form-validation.js"></script>
    <script src="{{ asset('') }}assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
@endpush
@push('js')

    {!! $dataTable->scripts() !!}
    <script>
        'use strict'

        var roleJs = function() {

            $('#role-table').on('click', '.edit', function() {
                let id = $(this).data('id');
                showLoading();
                $.ajax({
                    method: "GET",
                    url: `{{ url('konfigurasi/roles') }}/${id}/edit`,
                    success: function(result) {
                        callModal(result)
                        hideLoading();
                        storeValidation();
                    },
                    error: function(e) {
                        hideLoading();
                        const errors = e.responseJSON?.message;
                        if (errors) {
                            callModalError(errors);
                        }
                    }
                })
            })

            $('.add').on('click', function() {
                showLoading();
                $.ajax({
                    method: "GET",
                    url: "{{ route('roles.create') }}",
                    success: function(result) {
                        callModal(result);
                        hideLoading();
                        storeValidation();
                    },
                    error: function(e) {
                        hideLoading();
                        const errors = e.responseJSON?.message;
                        if (errors) {
                            callModalError(errors);
                        }
                    }
                })
            })

            function storeValidation() {
                const form = $('#form')

                form.validate({
                    submitHandler: function(_form) {

                        const method = _form.getAttribute('method')
                        const url = _form.getAttribute('action')
                        const formData = new FormData(_form)

                        saveLoading()
                        $.ajax({
                            method,
                            url,
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(result) {
                                saveLoading('hide', 'Simpan');
                                if (result.status == 'success') {
                                    $('.modal-global').modal('hide');
                                    window.LaravelDataTables['role-table'].ajax.reload();
                                }
                                toastr[result.status](result.message, {
                                    closeButton: true,
                                    tapToDismiss: false
                                });
                            },
                            error: function(e) {
                                saveLoading('', 'Simpan');
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
                })
            }

        }()
    </script>
@endpush
