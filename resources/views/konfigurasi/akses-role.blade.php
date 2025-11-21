@extends ('layout.master')
@push('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css">
@endpush
@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h4 class="card-title">Menu</h4>
                    </div><br />
                    <div class="card-body">
                        <div class="card-body">
                            {!! $dataTable->table() !!}
                        </div>
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
@endpush
@push('js')
    {!! $dataTable->scripts() !!}
    <script>
        'use strict'

        var menuJs = function() {

            $('#aksesrole-table').on('click', '.edit', function() {
                let id = $(this).data('id');
                showLoading();
                $.ajax({
                    method: "GET",
                    url: `{{ url('konfigurasi/akses-role') }}/${id}/edit`,
                    success: function(result) {
                        callModal(result)
                        hideLoading();
                        search();
                        storeValidation()
                    }
                })
            })

            function search(){
                const $rows = $('#permissions_data tr');
                $('#search_value').keyup(function() {
                    let val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
                    $rows.show().filter(function() {
                        var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                        return !~text.indexOf(val);
                    }).hide();
                });
            }

            function storeValidation() {
                const form = $('#form-akses')
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
                });
            }
        }()

    </script>
@endpush
