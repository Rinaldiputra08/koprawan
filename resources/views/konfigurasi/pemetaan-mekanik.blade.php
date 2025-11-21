@extends ('layout.master')
@push('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@push('css')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('') }}assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('') }}assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/css/plugins/forms/form-validation.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/vendors/css/extensions/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/css/plugins/forms/form-validation.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/vendors/css/extensions/sweetalert2.min.css">
@endpush
@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Data Pemetaan Mekanik</h4>
                    </div>
                    {{-- @if ($akses->tambah == 1) --}}
                        <br />
                        <div class="col-md-4">
                            @can('create ' . request()->segment(1) . '/' . request()->segment(2))
                                <button type="button" class="btn btn-gradient-primary add">Tambah</button>
                            @endcan
                        </div>
                    {{-- @endif --}}
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
    <script src="{{ asset('') }}assets/js/scripts/forms/pickers/form-pickers.js"></script>
@endpush
@push('js')
    {!! $dataTable->scripts() !!}
    <script>
        var PemetaanMekanikJs = function() {

            $('.add').on('click', function() {
                showLoading();
                $.ajax({
                    method: "GET",
                    url: "{{ route('konfigurasi.pemetaan-mekanik.create') }}",
                    success: function(result) {
                        callModal(result);
                        _loadFeather();
                        datePicker();
                        hideLoading();
                        getMekanik();
                        getStall();
                        storeValidation();
                    }
                })
            })

            $('#pemetaanmekanik-table').on('click', '.edit', function() {
                let id = $(this).data('id');
                showLoading();
                $.ajax({
                    method: "GET",
                    url: `{{ url('konfigurasi/pemetaan-mekanik') }}/${id}/edit`,
                    success: function(result) {
                        callModal(result)
                        _loadFeather();
                        datePicker();
                        hideLoading();
                        getMekanik();
                        getStall();
                        storeValidation();
                    }
                })
            })

            function getMekanik() {
                $('.find-mekanik').on('click', function() {
                    showLoading();
                    $.ajax({
                        method: "GET",
                        url: `{{ url('konfigurasi/pemetaan-mekanik/list-mekanik') }}`,
                        success: function(result) {
                            callModalFind(result)
                            hideLoading();
                            search();
                            postMekanik();
                            storeValidation();
                        }
                    })
                })
            }

            function getStall(){
                $('.find-stall').on('click', function() {
                    showLoading();
                    $.ajax({
                        method: "GET",
                        url: `{{ url('konfigurasi/pemetaan-mekanik/list-stall') }}`,
                        success: function(result) {
                            callModalFind(result)
                            hideLoading();
                            search();
                            postStall();
                            storeValidation();
                        }
                    })
                })
            }

            function search() {
                var $rows = $('#isi_data tr');
                $('#search').keyup(function() {
                    var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
                    $rows.show().filter(function() {
                        var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                        return !~text.indexOf(val);
                    }).hide();
                });
            }

            function postMekanik() {
                $('.post').on('click', function($nik) {
                    showLoading();
                    var table = document.getElementById("table_tampil_data");
                    var tbody = table.getElementsByTagName("tbody")[0];
                    tbody.onclick = function(e) {
                        e = e || window.event;
                        var data = [];
                        var target = e.srcElement || e.target;
                        while (target && target.nodeName !== "TR") {
                            target = target.parentNode;
                        }
                        if (target) {
                            var cells = target.getElementsByTagName("td");
                            for (var i = 0; i < cells.length; i++) {
                                data.push(cells[i].innerHTML);
                            }
                        }
                        // console.log(dt_split = dt.split(","));
                        $('#idMekanik').val(data[0].trim());
                        $('#mekanik').val(data[1].trim());
                        hideLoading();
                    };
                });
            }

            function postStall() {
                $('.post').on('click', function($nik) {
                    showLoading();
                    var table = document.getElementById("table_tampil_data");
                    var tbody = table.getElementsByTagName("tbody")[0];
                    tbody.onclick = function(e) {
                        e = e || window.event;
                        var data = [];
                        var target = e.srcElement || e.target;
                        while (target && target.nodeName !== "TR") {
                            target = target.parentNode;
                        }
                        if (target) {
                            var cells = target.getElementsByTagName("td");
                            for (var i = 0; i < cells.length; i++) {
                                data.push(cells[i].innerHTML);
                                dt = data.toString();
                            }
                        }
                        dt = data.toString();
                        dt_split = dt.split(",");
                        // console.log(dt_split = dt.split(","));
                        $('#idStall').val(dt_split[0].trim());
                        $('#stall').val(dt_split[1].trim());
                        hideLoading();
                    };
                });
            }

            function storeValidation() {
                const form = $('#form-mekanik')
                form.validate({
                    submitHandler: function() {
                        let formdata = form.serialize();
                        // console.log(formdata);
                        let url, method
                        let id = $(`input[name='id']`).val()

                        if (id === "") {
                            url = "{{ url('konfigurasi/pemetaan-mekanik') }}";
                            method = 'POST'
                        } else {
                            url = `{{ url('konfigurasi/pemetaan-mekanik/') }}/${id}`;
                            method = 'PUT'
                        }
                        // showLoading();
                        saveLoading()
                        $.ajax({
                            method,
                            url,
                            data: formdata,
                            success: function(result) {
                                // console.log(result);
                                // return;
                                saveLoading('hide', 'Simpan');
                                // console.log(result);
                                if (result.status == 'success') {
                                    $('.modal-global').modal('hide');
                                    window.LaravelDataTables['pemetaanmekanik-table'].ajax
                                        .reload();
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
                                        $(`select[name='${key}']`).parent().append(
                                            `<span class="error">${value}</span>`)
                                        $(`textarea[name='${key}']`).parent().append(
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
