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
                        @can('create '.request()->segment(1).'/'.request()->segment(2))
                            <button type="button" class="btn btn-gradient-primary add">Tambah</button>
                        @endcan
                        @can('update '.request()->segment(1).'/'.request()->segment(2))
                            <button type="button" class="btn btn-gradient-primary sort">Urutkan Menu</button>
                        @endcan
                    </div>
                    <div class="card-body">
                        {{-- <table class="datatables-ajax table">
                            <thead>
                                <tr>
                                    <th>Kode Menu</th>
                                    <th>Nama Menu</th>
                                    <th>URL</th>
                                    <th>Icon</th>
                                    <th>Main Menu</th>
                                    <th>Level Menu</th>
                                </tr>
                            </thead>
                        </table> --}}
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

        var menuJs = function() {

            $('#menu-table').on('click', '.edit', function() {
                let id = $(this).data('id');
                showLoading();
                $.ajax({
                    method: "GET",
                    url: `{{ url('konfigurasi/menu') }}/${id}/edit`,
                    success: function(result) {
                        callModal(result)
                        hideLoading();
                        storeValidation();
                    }, error: function(e){
                        hideLoading();
                        const errors = e.responseJSON?.message;
                        if (errors) {
                            callModalError(errors);
                        }
                    }
                })
            })

            $('.sort').on('click',function(){
                showLoading()
                $.ajax({
                    method: "PUT",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: `{{ url('konfigurasi/menu/sort') }}`,
                    success: function(result) {
                        hideLoading()
                        window.LaravelDataTables['menu-table'].ajax.reload()
                    }, error: function(e){
                        hideLoading();
                        const errors = e.responseJSON?.message;
                        if (errors) {
                            callModalError(errors);
                        }
                    }
                })
            })

            $('#menu-table').on('click', '.trash', function() {
                let id = $(this).data('id');
                confirmation(function() {
                    showLoading();
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        method: 'DELETE',
                        url: `{{ url('konfigurasi/menu') }}/${id}`,
                        success: function(m) {
                            hideLoading();
                            if (m.status == 'success') {
                                window.LaravelDataTables['menu-table'].ajax.reload();
                            }
                        }, error: function(e){
                        hideLoading();
                        const errors = e.responseJSON?.message;
                        if (errors) {
                            callModalError(errors);
                        }
                    }
                    })
                })
            })

            $('.add').on('click', function() {
                showLoading();
                $.ajax({
                    method: "GET",
                    url: "{{ route('menu.create') }}",
                    success: function(result) {
                        callModal(result);
                        hideLoading();
                        storeValidation();
                    }, error: function(e){
                        hideLoading();
                        const errors = e.responseJSON?.message;
                        if (errors) {
                            callModalError(errors);
                        }
                    }
                })
            })

            function storeValidation() {
                const form = $('#form-menu')
                form.validate({
                    submitHandler: function() {
                        let formdata = $('#form-menu').serialize();
                        // console.log(formdata);
                        let url, method
                        let id = $(`input[name='id']`).val()

                        if (id === "") {
                            url = "{{ route('menu.store') }}";
                            method = 'POST'
                        } else {
                            url = `{{ url('konfigurasi/menu') }}/${id}`;
                            method = 'PUT'
                        }
                        // showLoading();
                        saveLoading()
                        $.ajax({
                            method,
                            url,
                            data: formdata,
                            success: function(result) {
                                saveLoading('hide','Simpan');
                                // console.log(result);
                                if (result.status == 'success') {
                                    $('.modal-global').modal('hide');
                                    window.LaravelDataTables['menu-table'].ajax.reload();
                                }
                                toastr[result.status](result.message, {
                                    closeButton: true,
                                    tapToDismiss: false
                                });
                            },
                            error: function(e) {
                                // console.log(e);
                                saveLoading('','Simpan');
                                const errors = e.responseJSON?.errors;
                                if (errors) {
                                    for (const [key, value] of Object.entries(errors)) {
                                        if (key == 'jenis_bisnis') {
                                            $(`input[name='${key}']`).parent().parent().append(
                                                `<span class="error">${value}</span>`)
                                        } else {
                                            $(`input[name='${key}']`).parent().append(
                                                `<span class="error">${value}</span>`)
                                        }
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
