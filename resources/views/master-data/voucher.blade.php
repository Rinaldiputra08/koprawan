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
                        <h4 class="card-title">Voucher</h4>
                    </div><br />
                    <div class="col-md-4">
                        @can('create '.request()->segment(1).'/'.request()->segment(2))
                            <button type="button" data-url="{{ route('master-data.voucher.create') }}" class="btn btn-gradient-primary add">Tambah</button>
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
    <script src="{{ asset('') }}assets/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="{{ asset('') }}assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
    <script src="{{ asset('') }}assets/vendors/js/bootstrap-datepicker.js"></script>
@endpush
@push('js')

    {!! $dataTable->scripts() !!}
    <script>
        'use strict'

        var voucherJs = function() {
            const formAction = '#form-action', datatable = 'voucher-table'
        
            $('#'+datatable).on('click', '.action', function(e) {
                e.preventDefault()
                let url = this.getAttribute('href');
                let method = $(this).data('method');
                ajaxAction(url, method, function(){
                    showHideKriteria()
                    bsDatePicker()
                    callSelect2()
                    findUser()
                    removeUser()
                    storeAction(formAction, datatable)
                })
            })

            $('.add').on('click', function() {
                let url = $(this).data('url')
                ajaxAction(url, 'get', function(){
                    bsDatePicker()
                    showHideKriteria()
                    callSelect2()
                    storeAction(formAction, datatable)
                })
            })

            function showHideKriteria(){
                $('[name="has_kriteria"]').on('change', function(){
                    
                    let has_kriteria = $(this).val()

                    if (has_kriteria === '1') {
                        console.log(1);
                        $('#kriteria').parent().removeClass('d-none')
                    }else{
                        $('#kriteria').parent().addClass('d-none')
                    }
                })
            }

            function findUser(){
                $('#pilih_user').on('click', function(e){
                    
                    let url = `{{ url('master-data/voucher/cari-user') }}`
                    ajaxFind(url, 'get', function(){
                        selectUser()
                        search('#list-user tbody')
                    })
                })
            }
            
            function selectUser(){
                $('#list-user tbody').on('click', 'tr', function(e){
                    let rows = $(this).children(),
                        user_id = $(this).data('id')

                    if ($(`#table-user tbody tr[data-id="${user_id}"]`).length == 0) {
                        let tableRow = `<tr class="select-user" data-id="${user_id}">`
                            name = ''
                        $.each(rows, function(key, row){
                            var rowObject = $(row),
                                text = rowObject.text(),
                                column
    
                            if (rowObject.data('target') == 'id') {
                                column = `<input type="hidden" name="karyawan[]" value="${text}">`
                            }else{
                                column = `<td>${text}</td>`
                            }
    
                            if (rowObject.data('target') == 'nama') {
                                name = text
                            }
    
                            tableRow += column;
                        })
    
                        tableRow += `<td class="text-center"><button class="btn btn-sm btn-danger remove-user" data-name="${name}" type="button">X</button></td>`
    
                        $('#table-user tbody').prepend(tableRow)
                    }

                    $('.is-invalid').removeClass('is-invalid')
                    $('.invalid-feedback').remove()
                })
            }

            function removeUser(){
                $('#table-user').on('click','.remove-user', function(){
                        var user_name = $(this).data('name')
                        parent = $(this).parents('tr.select-user')
                        
                    let cb = function(){
                         parent.remove()
                         $('#alert-limit').remove()                      
                    }

                    let replaceOptions = {
                        title: 'Apakah anda yakin?',
                        text: `User ${user_name} akan dihapus dari list penerima voucher`
                    }
                    confirmation(cb, replaceOptions)
                })
            }

             function search(target){
                $('[name="search"]').on('keyup', debounce(function(){
                    $.ajax({
                        url: `{{ url('master-data/voucher/cari-user') }}`,
                        data: {
                            search: $(this).val()
                        },
                        method: 'get',
                        success: function(result){
                            $(target).html(result)
                        },
                        error: function(e){
                            const error = e.responseJSON?.message
                            console.log(error);
                        }
                    })
                }))
            }
            
        return {
                
            }
        }()


    </script>
@endpush