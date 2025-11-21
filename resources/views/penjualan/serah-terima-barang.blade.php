@extends('layout.master')
@push('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/vendors/css/forms/select/select2.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/vendors/css/extensions/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/vendors/css/bootstrap-datepicker.min.css">
    <style>
        .swal2-textarea{
            width: 100% !important;
            padding: 1rem 1rem !important;
            font-size: 1rem !important;
            color: #6e6b7b !important;
            border: 1px solid #d8d6de !important;
            border-radius: .357rem !important;
        }

        .swal2-textarea:focus{
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
                        <h4 class="card-title">Serah Terima Barang</h4>
                    </div><br>
                    <div class="col-12">
                        @can('create '. request()->path())
                            <button class="btn btn-gradient-primary add" data-url="{{ route('penjualan.serah-terima-barang.create') }}" type="button">Tambah</button>
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
    <script src="{{ asset('') }}assets/vendors/js/bootstrap-datepicker.min.js"></script>
@endpush
@push('js')
    {!! $dataTable->scripts() !!}

    <script>
        'use strict'

        var serahTerimaBarangJs = function(){
            const formAction = '#form-action', datatable = 'serahterima-table'

            $('.add').on('click', function(){
                let url = $(this).data('url')
                ajaxAction(url, 'get', function(){
                    callSelect2()
                    bsDatePicker()
                    findPenjualan()
                    storeAction(formAction, datatable)
                })
            })

            $(`#${datatable}`).on('click', '.action', function(e){
                e.preventDefault()
                let url = $(this).attr('href'),
                    method = $(this).data('method')
                    
                ajaxAction(url, method, ()=>{
                    callSelect2()
                    // batal('.modal-footer')
                })
            })

            function findPenjualan() {
                $('#nomor_penjualan').on('dblclick', function() {
                    let url = `{{ url('penjualan/serah-terima-barang/cari-penjualan') }}`
                    console.log(url);
                    ajaxFind(url, 'get', function(){
                        selectPenjualan()
                        search('#list-penjualan tbody')
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
                            console.log(error);
                        }
                    })
                }))
            }

            function selectPenjualan(){
                $('#list-penjualan tbody').on('click', 'tr', function(e) {
                    let id = $(this).data('id')

                    $.ajax({
                        url: `{{ url('penjualan/serah-terima-barang/data-penjualan') }}/${id}`,
                        type: 'get',
                        success: function (response) {
                            // console.log(response);
                            const penjualan_detail = response.penjualan_detail
                            $('#id_produk').val(id)                            
                            $('#nomor_penjualan').val(response.nomor)
                            $('#nama_karyawan').val(response.karyawan.nama)  

                            var total = null
                            var totalDiskon = null
                            var grandTotal = null

                            for (let i = 0; i < penjualan_detail.length; i++) {
                                var a = penjualan_detail[i].total_harga
                                var diskon = penjualan_detail[i].nominal_diskon
                                total += a
                                totalDiskon += diskon
                            }
                            grandTotal = parseInt(total) - parseInt(totalDiskon)

                            $('#total').html(total);
                            $('#potongan').html(totalDiskon);
                            $('#grand_total').html(grandTotal);
                            
                            let rows = ''
                            penjualan_detail.forEach(detail => {
                                rows += `<tr>
                                    <td>${detail.produk.nama}</td>
                                    <td>${detail.qty}</td>
                                    <td>${detail.harga}</td>
                                    <td>${detail.nominal_diskon}</td>
                                    <td>${detail.grand_total}</td>
                                    </tr>`
                                });
                                
                            const html = $('#table-item tbody').html(rows)

                            $('.is-invalid').removeClass('is-invalid')
                            $('.invalid-feedback').remove()

                        }
                    })
                })
            }
        }()
    </script>
@endpush