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
@endpush
@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h4 class="card-title"> Limit Karyawan</h4>
                    </div><br />
                    <div class="col-md-4">
                        @can('create '.request()->segment(1).'/'.request()->segment(2))
                            <button type="button" data-url="{{ route('master-data.limit-karyawan.create') }}" class="btn btn-gradient-primary add">Tambah</button>
                            <button type="button" class="btn btn-warning waves-effect waves-light filter">Filter Pencarian</button>
                        @endcan
                        <button class="btn btn-primary d-none back" onclick="location.reload()">Kembali</button>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning" role="alert">
                            <div class="alert-body">Periode input terakhir : {{dateFormat($data->periode.'01', 'm-Y')}}</div>
                        </div>
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
    <script src="{{ asset('') }}assets/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="{{ asset('') }}assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
    <script src="{{ asset('') }}assets/vendors/js/bootstrap-datepicker.js"></script>
@endpush
@push('js')

    {!! $dataTable->scripts() !!}
    <script>
        'use strict'

        var supplierJs = function() {
            const formAction = '#form-action', datatable = 'limit-karyawan-table'
        
            $('#'+datatable).on('click', '.action', function(e) {
                e.preventDefault()
                let url = this.getAttribute('href');
                let method = $(this).data('method');
                ajaxAction(url, method, function(){
                    storeAction(formAction, datatable)
                })
            })
            $('.filter').on('click', function() {
                showLoading();
                $.ajax({
                    method: 'GET',
                    url : `{{ url('master-data/limit-karyawan/filter') }}`,
                    success: function(result) {
                        callModal(result);
                        callSelect2();
                        showData();
                    },
                    error: function(e){
                        Swal.fire({
                            icon: 'error',
                            title: 'Wooy!!',
                            text: (e.responseJson?.message) ?? 'Terjadi kesalahan, coba lagi nanti',
                        })
                    },
                    complete: function(){
                        hideLoading();
                    }
                })
            })



            $('.add').on('click', function() {
               showLoading();
               $.ajax({
                method : 'get',
                url : '{{ route("master-data.limit-karyawan.create")}}',
                success: function(res){
                    $('.card-body').html(res)
                    bsDatePicker('.datepicker', {
                        format: 'mm-yyyy',
                        minViewMode: 1,
                        autoclose: true,
                        todayHighlight: true,
                        zIndexOffset: 9999
                    })

                   showListKaryawan();
                    $('.add').addClass('d-none')
                    $('.back').removeClass('d-none')
                    $('.filter').addClass('d-none')
                },
                error: function(e){
                    swal.fire({
                        icon: 'error',
                        title: 'Wooy!!',
                        text: (e.responseJson?.message) ?? 'Terjadi kesahalah, coba lagi nanti',
                    })
                },

                complete: function(){
                    hideLoading()
                }

            })
               
            })


            function showListKaryawan(jenis = 'tambah'){
                const select = $('#divisi')

                if(jenis == 'edit'){
                    var periode = $('[name="periode"]').val();
                }else{
                    var periode = null;
                }
                
                select.on('change', function(e){
                    let divisi = $(this).val()
                    $.ajax({
                        method: 'get',
                        data: {periode},
                        url : `{{ url('master-data/limit-karyawan/cari-karyawan')}}/${divisi}`,
                        success:function(res){
                            $('#list-karyawan').html(res)
                            storeValidation()
                            $('.btn-save').removeClass('d-none')
                        },
                        error: function(e){
                            swal.fire({
                                icon: 'error',
                                title: 'Wooy!!',
                                text: (e.responseJson?.message) ?? 'Terjadi kesahalah, coba lagi nanti',
                            })
                        },
                        complete: function(){
                            hideLoading()
                        }
    
                    })
                })
            }
            function showData(){
                const form = $('#form-filter')

                form.on('submit', function(e){
                    e.preventDefault()
                    let periode = $('[name="bulan"]').val()
                    $.ajax({
                        url:  `{{ url('master-data/limit-karyawan')}}/${periode}`,
                        method: 'GET',
                        data: form.serialize(),
                        success: function(result){
                            $('.modal-global').modal('hide');
                            saveLoading('hide', 'Cari Data');
                            $('.card-body').html(result);
                            callSelect2()
                            storeValidation()
                            showListKaryawan('edit');

                        },
                       error: function(e){
                        saveLoading('', 'Cari Data');
                        const errors = e.responseJson?.errors;
                        if(error) {
                            for (const [key, value] of object.entries(errors)){
                                $(`[name='${$key}']`).parent().append(
                                    `<span class="error">${value}</span>`)
                                
                                }
                            }
                        }
                    })
                });
            }
                
            function storeValidation(){
                const form = $('#form')

                form.on('submit', function(e){
                    e.preventDefault()
                    $.ajax({
                        url: form.attr('action'),
                        method: form.attr('method'),
                        data: form.serialize(),
                        success: function(res){
                           Swal.fire({
                                icon: res.status,
                                title: res.status == 'error' ? 'Wooy!!' : 'Sukses!!!',
                                text: res.message,
                            }).then(()=>{
                                if(res.status == 'success') {
                                window.location.href = "{{ url('master-data/limit-karyawan') }}"
                             }   
                            })
                        },
                        error: function(e){
                            const error = e.responseJSON?.errors

                            if ($.isEmptyObject(error) === false){
                                $.each(error, function(key, val){
                                    $(`[name="${key}"]`)
                                    .parent()
                                    .append(`<span class="error">${val}</span>`)
                                    $(`[name="${key}"]`).focus()

                                })
                            }else{
                                swal.fire({
                                    icon: 'error',
                                    title: 'Wooy!!',
                                    text: e.responseJSON?.message,

                                })
                            }
                        },
                        complete: function(){
                            saveLoading('hide', 'Simpan')
                        }
                    })
                })
            }
            
        return {
                
            }
        }()
    </script>
@endpush