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
                        <h4 class="card-title">Retur Penjualan</h4>
                    </div><br />
                    <div class="col-md-4">
                        @can('create '.request()->segment(1).'/'.request()->segment(2))
                            <button type="button" data-url="{{ route('penjualan.retur-penjualan.create') }}" class="btn btn-gradient-primary add">Tambah</button>
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

        var returnJs = function() {
            const formAction = '#form-action', datatable = 'retur-table'
        
            $('#'+datatable).on('click', '.action', function(e) {
                e.preventDefault()
                let url = this.getAttribute('href');
                let method = $(this).data('method');
                ajaxAction(url, method, function(){   
                    bsDatePicker()
                    callSelect2()
                    findTrans()
                    storeAction(formAction, datatable)
                })
            })

            $('.add').on('click', function() {
                let url = $(this).data('url')
                ajaxAction(url, 'get', function(){
                    bsDatePicker()
                    findTrans()
                    callSelect2()
                    storeAction(formAction, datatable)
                })
            })

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

            function findTrans(){
                $('#no_transaksi').on('dblclick', function(){
                  let url = `{{ url('penjualan/cari-transaksi') }}`
                    ajaxFind(url, 'get', function(){
                        selectTrans()
                        search('#list-trans tbody')
                    })
                })
            }

            function search(target){
                $('[name="search"]').on('keyup', debounce(function(){
                    $.ajax({
                        url: $(this).data('url'),
                        data: {
                            search: $(this).val()
                        },
                        method: 'get',
                        success: function(result){
                            $(target).html(result)
                        },
                        error: function(e){
                            const error = e.responseJSON?.message
                        }
                    })
                }))
            }

            function selectTrans(){
                $('#list-trans tbody').on('click', 'tr', function(e){
                    let rows = $(this).children()
                    $.each(rows, function(key, row){
                        var rowObject = $(row),
                            target = rowObject.data('target'),
                            text = rowObject.text()

                        $(`#${target}`).val(text)
                       
                    })
                })
            }

            
        return {
                
            }
        }()


    </script>
@endpush