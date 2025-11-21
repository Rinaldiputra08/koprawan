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
                    {{-- <div class="col-md-4">
                        <button type="button" class="btn btn-gradient-primary add">Tambah</button>
                    </div> --}}
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
    <script src="{{ asset('') }}assets/vendors/js/forms/validation/jquery.validate.min.js"></script>
    <script src="{{ asset('') }}assets/js/scripts/forms/form-validation.js"></script>
    <script src="{{ asset('') }}assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
@endpush
@push('js')

    {!! $dataTable->scripts() !!}
    <script>
        'use strict'

        var menuJs = function() {

            $('#akses-table').on('click', '.edit', function() {
                let id = $(this).data('id');
                showLoading();
                $.ajax({
                    method: "GET",
                    url: `{{ url('konfigurasi/akses') }}/${id}/edit`,
                    success: function(result) {
                        callModal(result)
                        hideLoading();
                        editAkses(id);
                    }
                })
            })
            function editAkses(id) {
                let edit = $('.update-akses')
                edit.on('change', function(e) {
                    let kode_menu = $(this).data('kode_menu');
                    let jenis = $(this).data('jenis');
                    let check = '';
                    let _token = $(`input[name='_token']`).val();
                    if (e.target.checked == true) {
                        check = '1';
                    } else {
                        check = '0';
                    }
                    showLoading();
                    $.ajax({
                        method:'PUT',
                        url:"{{ url('konfigurasi/akses') }}/"+kode_menu,
                        data: {_token:_token,level_user:id,check:check,jenis:jenis},
                        success:function(result){
                            hideLoading();
                            // console.log(result);
                        }
                    })
                })
            }

            function storeValidation() {
                const form = $('#form-menu')
                form.validate({
                    submitHandler: function() {
                        let formdata = $('#form-menu').serialize();
                        // console.log(formdata);
                        let url, method
                        let id = $(`input[name='id']`).val()

                        if (id === "") {
                            url = "{{ route('konfigurasi.menu.store') }}";
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
                                saveLoading('hide', 'Simpan');
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
                });
            }
        }()

    </script>
@endpush
