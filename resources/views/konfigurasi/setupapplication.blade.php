@extends('layout.master')
@push('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/vendors/css/extensions/sweetalert2.min.css">
@endpush
@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h4 class="card-title">Daftar Setup</h4>
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
    <script src="{{ asset('') }}assets/vendors/js/json-editor/"></script>
@endpush
@push('js')
    {!! $dataTable->scripts() !!}

    <script>
        'use strict'

        var setupJs = function(){
            const formAction = '#form-action',
                datatable = 'setupapplication-table'

            $('.add').on('click', function(){
                let url = `{{ route('setup-aplikasi.create') }}`

                ajaxAction(url, 'GET', function(){
                    parseJSON()
                    storeAction(formAction, datatable)
                })
            })

            $('#'+datatable).on('click', '.action', function(e){
                e.preventDefault()
                let url = $(this).attr('href'),
                    method= $(this).data('method')

                ajaxAction(url, method, function(){
                    parseJSON()
                    parseJSONImmediately()
                    storeAction(formAction, datatable)
                })
            })

            function parseJSONImmediately(){
                let tipe = $('[name="nilai"]').data('tipe')
                if (tipe == 'json') {
                    var jsonparse = JSON.parse($('[name="nilai"]').val()),
                        jsonpretty = JSON.stringify(jsonparse, undefined, 4)

                    $('[name="nilai"]').val(jsonpretty)
                }
            }

            function parseJSON(){
                $('.radio-jenis').on('change', function(e){
                    var jenis = $('[name="jenis"]:checked').val(),
                        textarea = $('[name="nilai"]')

                    textarea
                        .removeClass('is-invalid')
                        .parents('.form-group')
                        .find('.error')
                        .remove()

                    if (jenis == 'json') {
                        try {
                            var jsonparse = JSON.parse(textarea.val()),
                                jsonpretty = JSON.stringify(jsonparse, undefined, 4)

                            textarea.val(jsonpretty)
                        } catch (error) {
                            textarea
                                .addClass('is-invalid')
                                .parents('.form-group')
                                .append('<span class="error">Kolom nilai harus format JSON string</span>')
                        }
                    }
                })

                $('[name="nilai"]').on('blur', function(e){
                    var jenis = $('[name="jenis"]:checked').val()

                    $(this)
                        .removeClass('is-invalid')
                        .parents('.form-group')
                        .find('.error')
                        .remove()

                    if (jenis == 'json') {
                        try {
                            var jsonparse = JSON.parse($(this).val()),
                                jsonpretty = JSON.stringify(jsonparse, undefined, 4)

                            $(this).val(jsonpretty)
                        } catch (error) {
                            $(this)
                                .addClass('is-invalid')
                                .parents('.form-group')
                                .append('<span class="error">Kolom nilai harus format JSON string</span>')
                        }
                    }
                })
            }
        }()
    </script>
@endpush