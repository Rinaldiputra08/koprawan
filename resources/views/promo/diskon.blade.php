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
                        <h4 class="card-title">Diskon</h4>
                    </div><br />
                    <div class="col-md-4">
                        @can('create '.request()->segment(1).'/'.request()->segment(2))
                            <button type="button" data-url="{{ route('promo.diskon.create') }}" class="btn btn-gradient-primary add">Tambah</button>
                        @endcan
                    </div>
                    <div class="col-md-6 mt-1">
                        <label for="">Filter</label>
                        <select data-url="{{ route('promo.diskon.index') }}" name="filter" id="filter" class="form-control">
                            <option value="">Semua Data</option>
                            <option value="berlaku">Belaku</option>
                            <option value="tidak berlaku">Tidak Belaku</option>
                        </select>
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

        var diskonJs = function() {
            const formAction = '#form-action',
            datatable = 'diskon-table'

            function callDatePicker(){
                $('.datepicker').datepicker({
                    language: "id",
                    autoclose: true,
                    todayHighlight: true,
                    format: 'dd-mm-yyyy',
                    clearBtn: true
                })
                
            }

            $('[name="filter"]').on('change', function(){
                let url = $(this).data('url')
                if(this.value != ''){
                    url += '?filter='+this.value
                }
                reloadDataTable(url)
            })

            function reloadDataTable(url){
                window.LaravelDataTables['diskon-table'].ajax.url(url).load()
            }

            function cariProduk(){
                $('.cari-produk').on('click', function(e){
                    let url = $(this).data('url')
                    showLoading();
                    $.ajax({
                        method: "GET",
                        url,
                        success: function(result) {
                            callModalFind(result)
                            filterProduk(url)
                            pilihProduk()
                            hideLoading()
                        },
                        error: function($result){
                            hideLoading()
                        }
                    })
                })
            }

            function filterProduk(url)
            {
                const _table = $('#list-produk')
                const filter = _table.find('[name="filter_produk"]')
                let tbody = _table.find('tbody');
                filter.on('keyup', debounce(function(){
                    showLoading()
                    $.ajax({
                        method: "GET",
                        url: `${url}?filter=${this.value}`,
                        success: function(result) {
                            tbody.html(result)
                            hideLoading()
                        },
                        error: function($result){
                            hideLoading()
                        }
                    })
                },500))
            }

            function pilihProduk() {
                const _table = $('#list-produk')
                    let tbody = _table.find('tbody');
                    tbody.on('click', function(e){

                        e = e || window.event;
                        let dataCell = [];
                        let target = e.srcElement || e.target;
                        while (target && target.nodeName !== "TR") {
                            target = target.parentNode;
                        }
                        if (target) {
                            let cells = target.getElementsByTagName("td");
                            for (let i = 0; i < cells.length; i++) {
                                dataCell.push(cells[i].innerHTML.trim());
                            }
                        }
    
                        let input = ['produk_id','foto','kode_produk','nama_produk']
                        input.forEach(function(dt, i){
                            $(`[name="${dt}"]`).val(dataCell[i]);
                        })
                    })
            }
        
            $('#'+datatable).on('click', '.action', function(e) {
                e.preventDefault()
                let url = this.getAttribute('href');
                let method = $(this).data('method');
                ajaxAction(url, method, function(){
                    cariProduk()
                    callDatePicker()
                    callSelect2()
                    storeAction(formAction, datatable)
                })
            })

            $('.add').on('click', function() {
                let url = $(this).data('url')
                ajaxAction(url, 'get', function(){
                    cariProduk()
                    callDatePicker()
                    callSelect2()
                    storeAction(formAction, datatable)
                })
            })
            
        return {
                
            }
        }()
    </script>
@endpush