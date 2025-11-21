@extends ('layout.master')
@push('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/vendors/css/extensions/sweetalert2.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/vendors/css/forms/select/select2.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/vendors/css/bootstrap-datepicker.min.css">
<style>
    .swal2-input{
        width: 100% !important;
        padding: 1rem 1rem !important;
        font-size: 1rem !important;
        color: #6e6b7b !important;
        border: 1px solid #d8d6de !important;
        border-radius: .35rem !important;
    }

    .swal2-input:focus{
        box-shadow: none !important;
        outline: none !important;
        border: 1px solid #7367f0 !important;
    }
</style>
@endpush
@section('content')
<section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title">Closing Produk</h4>
                </div><br />
                <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a href="#in-progress" id="progress-tab" class="nav-link {{ !request()->has('periode') ? ' active' : '' }}" data-toggle="tab" role="tab" aria-selected="true">
                                <i data-feather="loader"></i>
                                    IN PROGRESS
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#closed" id="closed-tab" class="nav-link {{ request()->has('periode') ? ' active' : '' }}" data-toggle="tab" role="tab" aria-selected="false">
                                <i data-feather="lock"></i>
                                    CLOSED
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane {{ !request()->has('periode') ? ' active' : '' }}" id="in-progress" role="tabpanel" aria-labelledby="progress-tab">
                            <div class="row dont-remove">
                                <div class="col-md-3 col-12">
                                    <div class="form-group">
                                        <label for="">Periode</label>
                                        <input type="text" name="period" readonly class="form-control datepicker">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <button class="btn btn-primary btn-tampilkan" type="button">Tampilkan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane {{ request()->has('periode') ? ' active' : '' }}" id="closed" role="tabpanel" aria-labelledby="progress-tab">
                            <div class="row dont-remove">
                                <div class="col-md-3 col-lg-4 col-12">
                                    <div class="form-group">
                                        <label for="">Pilih Periode</label>
                                        <select name="periode" class="form-control select2">
                                            <option></option>
                                            @foreach ($list_closing as $periode)
                                            <option value="{{ $periode }}" {{ request('periode') == $periode ? 'selected' : '' }}>{{ dateFormat($periode.'01', 'm-Y') }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <button class="btn btn-primary btn-proses" type="button">Proses</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
<script src="{{ asset('') }}assets/vendors/js/forms/select/select2.full.min.js"></script>
<script src="{{ asset('') }}assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
<script src="{{ asset('') }}assets/vendors/js/bootstrap-datepicker.js"></script>
<script src="{{ asset('')}}assets/vendors/js/charts/chart.min.js"></script>
@endpush
@push('js')

<script>
    'use strict'

    var penjualanJs = function() {
        $('.btn-tampilkan').on('click', function(e) {
            e.preventDefault()
            
            $('#in-progress .row:not(.dont-remove)').remove()
            const periode = $('[name="period"]').val();  
            
            if (periode == '') {
                $('#in-progress').append( showError('Periode harus disi'))  
                return
            }

            showLoading()
            $.ajax({
                method: 'get',
                url: `{{url('closing/get-data')}}/${periode}`,
                success: function(result) {
                    if (result.status == 'error') {
                        result = showError(result.message)
                    }
                    $('#in-progress').append(result)
                    proses();
                },
                error: function(e) {
                    showError(e.responseJSON?.message)
                },
                complete: function() {
                    hideLoading();
                }
            })       
        })
        
        
        function showError(message){
            return `<div class="row">
                        <div class="col-12">
                            <div class="alert alert-danger">
                                <div class="alert-body">${message}</div>
                            </div> 
                        </div> 
                    </div>`
        }

        function proses(){
            $('.simpan').on('click', function(){
                Swal.fire({
                    icon: 'warning',
                    title: 'Closing Produk',
                    text: 'Data akan disimpan secara permanen setelah closing',
                    showCancelButton: true,
                    input: 'password',
                    inputPlaceholder: 'Masukan kata sandi',
                    inputAttributes: {
                        placeholder: 'Masukan kata sandi',
                        required: true,
                    },
                    inputValidator: (value) => {
                        if(!value){
                            return 'Harus memasukan kata sandi'
                        }
                    },
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-outline-danger ml-1',
                        input: 'form-control',
                    },

                    buttonsStyling: false,
                    showLoaderOnConfirm: true,
                    preConfirm: (password) => {
                        let formData = new FormData();
                        let periode = $('[name="period"]').val()
                        formData.append('password', password);
                        formData.append('periode', periode);

                        return fetch(`{{ url('closing/closing-produk') }}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            },
                            body: formData
                        }).then((response) => {
                            if(!response.ok){
                                throw new Error(response.statusText)
                            }
                            return response.json()
                        }).then((data)=>{
                            if(data.status == 'error') {
                                throw new Error(data.message)
                            }
                            return data
                        }).catch((error) => {
                            Swal.showValidationMessage(error)
                        })
                    }

                 }).then((response) => {
                    if (response.isConfirmed) {
                         toastr[response.value.status](response.value.message, {
                            closeButton: true,
                            tapToDismiss: false
                        })
                        Swal.close()
                        window.location.reload()
                    }
                 })
            })
        }

        function closed(periode){
            showLoaderButton()
            $('#closed .row:not(.dont-remove)').remove()
            $.ajax({
                url: `{{ url('closing/get-closed') }}/${periode}`,
                method: 'GET',
                success: function(response){
                    $('#closed').append(response)
                    $('.simpan').addClass('d-none')
                    approve()
                    // reClosing()
                },
                error: function(e){
                    const error = e.responseJSON?.message
                    showErrorSwal(error)
                },
                complete: function(){
                    hideLoaderButton()
                }
            })
        }
        
        eventClosed()
        function eventClosed(){
            $('.btn-proses').on('click', function(e){
                let periode = $('[name="periode"]').val()
                const url = new URL(window.location)

                $('[name="periode"]').removeClass('is-invalid')
                $('div.invalid-feedback').remove()

                if (periode) {
                    let periodeBefore = url.searchParams.get('periode')
                    if (periodeBefore != periode) {
                        url.searchParams.set('periode', periode)
                        window.history.pushState({}, '', url)
                        closed(periode)
                    }
                }else{
                    $('[name="periode"]')
                        .addClass('is-invalid')
                        .parents('.form-group')
                        .append('<div class="invalid-feedback">Periode belum dipilih</div>')
                }
                
            })
        }

        function showLoaderButton(){
            let button = $('.btn-proses')

            button.attr('disabled', 'disabled')
                .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="ml-25 align-middle"> Memproses data...</span>')
        }

        function showErrorSwal(message){
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: message,
                customClass: {
                    confirmButton: 'btn btn-primary',
                },
                buttonsStyling: false,
            })
        }

        function hideLoaderButton(){
            let button = $('.btn-proses')

            button.removeAttr('disabled').html('Proses')
        }

        eventInProgress()
        function eventInProgress() {
            $('#progress-tab').on('click', function(){
                let tabpane = $('#in-progress').html()
                const url = new URL(window.location)
                url.searchParams.delete('periode')
                window.history.pushState({}, '', url)

                if (tabpane == '') {
                    inProgressInsentif()
                }
            })
        }

        closedTabEvent()
        function closedTabEvent(){
            $('#closed-tab').on('click', function(){
                let periode = $('[name="periode"]').val()
                if (periode) {
                    const url = new URL(window.location)
                    url.searchParams.set('periode', periode)
                    window.history.pushState({}, '', url)
                }
            })
        }

         checkParams()
        function checkParams(){
            const params = new URLSearchParams(window.location.search)
            if (params.get('periode')) {
                closed(params.get('periode'))
            }else{
                // inProgressInsentif()
            }
        }

        bsDatePicker('.datepicker', {
            format: 'mm-yyyy',
            minViewMode: 1,
            autoclose: true,
            todayHighlight: true,
            zIndexOffset: 9999
        })

       
        function approve(){
            $('.approve').on('click', function(){
                 let periode = $('[name="periode"]').val() 
                let url = `{{url('closing/approval-closed')}}/${periode}`
                 ajaxAction(url, 'get', function(){
                    prosesApprove();
                })
            })
        
        }

        function prosesApprove(){
            $('#form-action').on('submit', function(e){
                e.preventDefault()
                saveLoading()
                let form = $(this)
                $.ajax({

                    url: form.attr('action'),
                    method: 'PUT',
                    data: form.serialize(),
                    success: function(response){
                        toastr[response.status](response.message, {
                            closeButton: true,
                            tapToDismiss: false
                        })

                        if (response.status == 'success') {
                            window.location.reload()
                            $('.modal-global').modal('hide');
                        }    
                    },
                    error: function(e){
                        let errors = e.responseJSON?.errors
                        if (errors) {
                            let i = 0
                            for (const [key, value] of Object.entries(errors)) {  
                                if (i == 0) {
                                    $(`[name="${key}"]`).focus()
                                }
                                i++;
                                form.find(`[name^='${key}']`).addClass('is-invalid').parents('.form-group').append(`<span class="error">${value}</span>`)
                            }
                        }
                    },
                    complete: function(){
                         saveLoading('hide', 'Proses')
                    }
                })
            })
        }

    }()
</script>
@endpush