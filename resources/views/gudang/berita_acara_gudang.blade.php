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
                        <h4 class="card-title">Berita Acara Gudang</h4>
                    </div><br>
                    <div class="col-12">
                        @can('create '. request()->path())
                            <button class="btn btn-gradient-primary add" data-url="{{ route('gudang.berita-acara-gudang.create') }}" type="button">Tambah</button>
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

        var beritaAcaraGudangJs = function(){
            const formAction = '#form-action', datatable = 'berita-acara-gudang-table'

            $('.add').on('click', function(){
                let url = $(this).data('url')
                ajaxAction(url, 'get', function(){
                    callSelect2()
                    bsDatePicker()
                    selectProdukByKode()
                    findProduct()
                    addItem()
                    editItem()
                    removeItem()
                    selectItem()
                    storeAction(formAction, datatable)
                })
            })

            $(`#${datatable}`).on('click', '.action', function(e){
                e.preventDefault()
                let url = $(this).attr('href'),
                    method = $(this).data('method')

                ajaxAction(url, method, ()=>{
                    callSelect2()
                    batal('.modal-footer')
                })
            })


            batal()
            function batal(selector = null){
                if (!selector) {
                    selector = '#'+datatable
                }
                $(selector).on('click', '.batal', function(e){
                    e.preventDefault()
                    let url = $(this).attr('href'),
                        method = $(this).data('method')
                    
                    let cb = function(keterangan){
                        let data = {
                            keterangan,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        }
    
                        ajaxAction(url, method, function(result){
                            if (result.status == 'success') {
                                $('.modal-global').modal('hide');
                                window.LaravelDataTables['berita-acara-gudang-table'].ajax.reload();
                            }
                            toastr[result.status](result.message, {
                                closeButton: true,
                                tapToDismiss: false
                            });
                        }, data)
                    }
    
                    let replaceOptions = {
                        title: 'Batalkan?',
                        text: 'Berita acara akan dibatalkan dan tidak bisa dikembalikan',
                        input: 'textarea',
                        confirmButtonText: 'Ya, Batalkan!',
                        inputAttributes: {
                            placeholder: 'Isi keterangan',
                            required: true
                        },
                        inputValidator: (val)=>{
                            if (!val) {
                                return 'Keterangan harus diisi'
                            }
                        },
                    }
                    
                    fixBootstrapModal('.modal-global[tabindex="-1"]')
                    confirmation(cb, replaceOptions, ()=>{
                        restoreBootstrapModal()
                    })
                })
            }

            function selectProdukByKode(){
                $('#kode_produk').on('change', function(e){
                    let kode = $(this).val(),
                        url = `{{ url('master-data/produk/cari') }}`
                    
                    $('.is-invalid').removeClass('is-invalid')
                    $('.invalid-feedback').remove()

                    ajaxSelect(url, 'get', function(result){
                        if (result) {
                            $.each(result, function(key, value){
                                $(`#${key}`).val(value)
                                if (key == 'harga_satuan') {
                                    $('#sub_total').val(value)
                                }
                            })
                        }else{
                            $('#nama_produk').val('')
                            $('#harga_satuan').val('')
                            $('#stok').val('')
                            $('#sub_total').val('')
                        }

                        $('#diskon, #nominal_diskon').val(0)
                        $('#qty').val(1)
                    }, {kode})
                })
            }

            function findProduct(){
                $('#kode_produk').on('dblclick', function(){
                    let url = `{{ url('master-data/produk/cari') }}`
                    ajaxFind(url, 'get', function(){
                        selectProduk()
                        search('#list-produk tbody')
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

            function selectProduk(){
                $('#list-produk tbody').on('click', 'tr', function(e){
                    let rows = $(this).children()

                    $.each(rows, function(key, row){
                        var rowObject = $(row),
                            target = rowObject.data('target'),
                            text = rowObject.text()

                        $(`#${target}`).val(text)
                        if (target == 'harga_satuan') {
                            $('#sub_total').val(text)
                        }
                    })

                    $('#diskon, #nominal_diskon').val(0)
                    $('#qty').val(1)

                    $('.is-invalid').removeClass('is-invalid')
                    $('.invalid-feedback').remove()
                })
            }

            function addItem(){
                $('.add-item').on('click', function(){
                    let parent = $(this).parents('.detail-produk'),
                        dataProduk = parent.find('input, textarea')

                    parent.find('.is-invalid')
                        .removeClass('is-invalid')

                    $('.invalid-feedback').remove()

                    var shouldAppend = true
                    $.each(dataProduk, function(key, input){
                        var id = input.id,
                            value = $(input).val()
                        
                        if (value == '') {
                            var name = id.replace('_', ' ')
                            $(input)
                                .addClass('is-invalid')
                                .parents('.form-group')
                                .append(`<span class="invalid-feedback">Kolom ${name} harus diisi</span>`)

                            shouldAppend = false
                        }
                    })

                    if (shouldAppend) {
                        appendRow(dataProduk)
                    }
                })
            }

            function removeItem(){
                $('#table-item').on('click','.remove-item', function(){
                    let produk_name = $(this).data('name'),
                        parent = $(this).parents('tr.select-item')
                    
                    let cb = function(){
        
                        parent.remove()
                    }

                    let replaceOptions = {
                        title: 'Apakah anda yakin?',
                        text: `Produk ${produk_name} akan dihapus dari list pemesanan`
                    }
                    confirmation(cb, replaceOptions)
                })
            }

            function selectItem(){
                $('#table-item').on('click','.select-item [data-key]', function(e){
                    let columns = $(this).parent().children('td'),
                        id = $(this).parents('tr.select-item').attr('id')

                    $.each(columns, function(key, column){
                        var value = column.innerText,
                            selector = $(column).data('key')

                        $(`#${selector}`).val(value)
                    })

                    $('.edit-item')
                        .val(id)
                        .removeClass('hidden')
                    $('.add-item').addClass('hidden')

                })
            }

            function editItem(){
                $('.edit-item').on('click', function(e){
                    let parent = $(this).parents('.detail-produk'),
                        dataProduk = parent.find('input'),
                        id = $(this).val()
                    
                    parent.find('.is-invalid')
                        .removeClass('is-invalid')

                    $('.invalid-feedback').remove()

                    var shouldEdit = true
                    $.each(dataProduk, function(key, input){
                        var id = input.id,
                            value = $(input).val()
                        
                        if (value == '' && id != 'id_produk') {
                            var name = id.replace('_', ' ')
                            $(input)
                                .addClass('is-invalid')
                                .parents('.form-group')
                                .append(`<span class="invalid-feedback">Kolom ${name} harus diisi</span>`)

                            shouldEdit = false
                        }
                    })

                    if (shouldEdit) {  
                        function editRow(){
                            $(`tr#${id} td[data-key='diskon']`).text($('#diskon').val())
                            $(`tr#${id} td[data-key='nominal_diskon']`).text($('#nominal_diskon').val())
                            $(`tr#${id} td[data-key='qty']`).text($('#qty').val())
                            $(`tr#${id} td[data-key='sub_total']`).text($('#sub_total').val())
                            $(`tr#${id} input[name^="qty"]`).val($('#qty').val())
                            $(`tr#${id} input[name^="nominal_diskon"]`).val($('#nominal_diskon').val())
                        }

                        editRow()
                        $('.edit-item').addClass('hidden')
                        $('.add-item').removeClass('hidden')
                        
                        $.each(dataProduk, function(key, input){
                            $(input).val('')
                        })
                    }
                })
            }

            function appendRow(dataProduk){
                let target = $('#table-item tbody'),
                    data = []

                $.each(dataProduk, function(key, input){
                    var id = input.id,
                        value = $(input).val()

                    data[id] = value
                })

                let findRow = target.children(`tr#${data['kode_produk']}`),
                    dataAppend = buildRow(data)

                if (findRow.length) {
                    let cb = function(){

                        findRow.remove()
                        target.prepend(dataAppend)

                        $.each(dataProduk, function(key, input){
                            $(input).val('')
                        })
                    }

                    let replaceOptions = {
                        title: `Produk ${data['nama_produk']} sudah ada`,
                        text: 'Pilih Ya untuk mengganti produk yang sudah ada',
                        confirmButtonText: 'Ya, Ganti!'
                    }

                    confirmation(cb, replaceOptions, ()=>{return})
                    
                }else{
                    target.prepend(dataAppend)
                    
                    $.each(dataProduk, function(key, input){
                        $(input).val('')
                    })
                }
            }

            function buildRow(data){
                return `<tr id="${data['kode_produk']}" class="cursor-pointer select-item">
                        <td data-key="kode_produk">${data['kode_produk']}</td>
                        <td data-key="nama_produk">${data['nama_produk']}</td>
                        <td class="text-center" data-key="qty">${data['qty']}</td>
                        <td class="text-center" data-key="keterangan">${data['keterangan']}</td>
                        <td class="text-center"><button class="btn btn-sm btn-danger remove-item" data-name="${data['nama_produk']}" type="button">X</button></td>
                        <input type="hidden" name="qty[${data['id_produk']}]" value="${data['qty']}">
                        <input type="hidden" name="keterangan_produk[${data['id_produk']}]" value="${data['keterangan']}">
                        <input type="hidden" name="nominal_diskon[${data['id_produk']}]" value="${data['nominal_diskon']}">
                    </tr>`
            }

        }()
    </script>
@endpush