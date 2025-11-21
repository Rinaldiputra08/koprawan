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
                        <h4 class="card-title">Penerimaan Produk</h4>
                    </div><br>
                    <div class="col-12">
                        @can('create '. request()->path())
                            <button class="btn btn-gradient-primary add" data-url="{{ route('pembelian.penerimaan-produk.create') }}" type="button">Tambah</button>
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

        var penerimaanProdukJs = function(){
            const formAction = '#form-action', datatable = 'penerimaanproduk-table'

            $('.add').on('click', function(){
                let url = $(this).data('url')
                ajaxAction(url, 'get', function(){
                    callSelect2()
                    bsDatePicker()
                    referensi()
                    selectProdukByKode()
                    findProduct()
                    diskonPercent()
                    diskonNominal()
                    qty()
                    addItem()
                    editItem()
                    removeItem()
                    selectItem()
                    checkboxPemesanan()
                    findPemesanan()
                    hargaSatuan()
                    storeAction(formAction, datatable)
                })
            })

            $(`#${datatable}`).on('click', '.action', function(e){
                e.preventDefault()
                let url = $(this).attr('href'),
                    method = $(this).data('method')

                ajaxAction(url, method, ()=>{
                    callSelect2()
                    bsDatePicker()
                    storeAction(formAction, datatable)
                    batal('.modal-footer')
                })
            })

            function checkboxPemesanan(){
                $('[name="pemesanan"]').on('change', function(e){
                    let checked = e.currentTarget.checked
                    
                    if (checked) {
                        $('.pemesanan-group').removeClass('d-none')
                    }else{
                        $('.pemesanan-group')
                            .addClass('d-none')
                            .find('input')
                            .val('')
                        $('.row.detail-produk').removeClass('d-none')
                        $('[name="supplier"]').val(null).trigger('change')
                        $('#table-item tbody').html('')
                        $('#total').text(0)
                        $('#ppn').text(0)
                        $('#grand_total').text(0)
                    }
                })
            }

            function referensi(){
                $('.modal-footer').on('click', '.referensi-order', function(e){
                    e.preventDefault()
                    let url = $(this).attr('href'),
                        method = $(this).data('method')

                    ajaxFind(url, method, ()=>{
                        selectReferensi()
                        search('#list-penerimaan tbody')
                    }) 
                })
            }

            function selectReferensi(){
                $('#list-penerimaan tbody').on('click', 'tr', function(e){
                    let id = $(this).data('id'),
                        url = $(this).data('url') + `?id=${id}`
                    ajaxSelect(url, 'GET', function(result){
                        if (result) {
                            var number_format = new Intl.NumberFormat('id-ID')
                            $.each(result, function(key, value){
                                if (Array.isArray(value)) {
                                    var data = [], diskon, harga_beli, row = ''
                                    
                                    value.forEach(item => {
                                        harga_beli = item.produk.harga_beli
                                        diskon = (item.diskon/harga_beli) * 100
                                        data['id_produk'] = item.produk_id
                                        data['kode_produk'] = item.produk.kode
                                        data['nama_produk'] = item.produk.nama
                                        data['harga_satuan'] = number_format.format(harga_beli)
                                        data['stok'] = number_format.format(item.produk.stock_free)
                                        data['diskon'] = diskon
                                        data['nominal_diskon'] = number_format.format(item.diskon)
                                        data['sub_total'] = number_format.format(item.sub_total)
                                        data['qty'] = number_format.format(item.qty)

                                        row += buildRow(data)
                                    });                                    
                                    $('#table-item tbody').html(row)
                                }else{
                                    if (['supplier', 'keterangan'].includes(key)) {
                                        $(`[name="${key}"]`).val(value)
                                        $(`[name="${key}"]`).trigger('change')
                                    }else if(key == 'tanggal_penerimaan'){
                                        $('[name="tanggal_penerimaan"]').datepicker('update', value)
                                    }
                                }
                            })

                            if (result.pemesanan) {
                                $('[name="pemesanan"]').prop('checked', true)
                                $('.pemesanan-group').removeClass('d-none')
                                $('.row.detail-produk').addClass('d-none')
                                $('[name="nomor_pemesanan"]').val(result.pemesanan.nomor)
                                $('[name="tanggal_pemesanan"]').val(result.tanggal_pemesanan)
                            }else{
                                $('[name="pemesanan"]').prop('checked', false)
                                $('.pemesanan-group').addClass('d-none')
                                $('.row.detail-produk').removeClass('d-none')
                            }

                            if (result.nomor_tagihan != '-') {
                                $('#dengan').prop('checked', true)
                            }else{
                                $('#tanpa').prop('checked', true)
                            }

                            calculate()
                        }
                    })
                })
            }

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
                                window.LaravelDataTables['penerimaanproduk-table'].ajax.reload();
                            }
                            toastr[result.status](result.message, {
                                closeButton: true,
                                tapToDismiss: false
                            });
                        }, data)
                    }
    
                    let replaceOptions = {
                        title: 'Batalkan?',
                        text: 'Penerimaan akan dibatalkan dan tidak bisa dikembalikan',
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

            function findPemesanan(){
                $('[name="nomor_pemesanan"]').parent().on('click', '.cursor-pointer', function(){
                    let url = `{{ url('pembelian/pemesanan-produk/cari') }}?active=true`
                    ajaxFind(url, 'get', function(){
                        selectPemesanan()
                        search('#list-pemesanan tbody')
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

            function selectPemesanan(){
                $('#list-pemesanan tbody').on('click', 'tr', function(e){
                    let id = $(this).data('id'),
                        url = $(this).data('url') + `?id=${id}&active=true`
                    ajaxSelect(url, 'GET', function(result){
                        if (result) {
                            var number_format = new Intl.NumberFormat('id-ID')
                            $.each(result, function(key, value){
                                if (Array.isArray(value)) {
                                    var data = [], diskon, harga_beli, row
                                    
                                    value.forEach(item => {
                                        harga_beli = item.produk.harga_beli
                                        diskon = (item.diskon/harga_beli) * 100
                                        data['id_produk'] = item.produk_id
                                        data['kode_produk'] = item.produk.kode
                                        data['nama_produk'] = item.produk.nama
                                        data['harga_satuan'] = number_format.format(harga_beli)
                                        data['stok'] = number_format.format(item.produk.stock_free)
                                        data['diskon'] = diskon
                                        data['nominal_diskon'] = number_format.format(item.diskon)
                                        data['sub_total'] = number_format.format(item.sub_total)
                                        data['qty'] = number_format.format(item.qty)

                                        row += buildRow(data)
                                    });
                                    $('#table-item tbody').html(row)
                                }else{
                                    if (['supplier', 'keterangan', 'tanggal_pemesanan', 'nomor_pemesanan'].includes(key)) {
                                        $(`[name="${key}"]`).val(value)
                                        $(`[name="${key}"]`).trigger('change')
                                    }
                                }
                            })
                            calculate()

                            $('.row.detail-produk').addClass('d-none')
                        }
                    })
                })
            }

            function diskonPercent(){
                $('#diskon').on('keyup', function(e){
                    let diskon = $(this).val(),
                        harga = ($('#harga_satuan').val()),
                        qty = ($('#qty').val()).replace(/[^\d]/g, ""),
                        number_format = new Intl.NumberFormat('id-ID'),
                        sub_total = parseInt(harga) * parseInt(qty)
                    
                    if (diskon == '') {
                        diskon = 0
                        $('#nominal_diskon').val('')
                    }
                    diskon = parseInt(diskon)
                    if (diskon > 100) {
                        $(this).val(100)
                        return
                    }

                    harga = parseInt(harga.replace(/[^\d]/g, ""))

                    var nominal = harga * (diskon/100)
                    
                    sub_total = (harga - nominal) * parseInt(qty)
                    
                    $('#nominal_diskon').val(number_format.format(nominal))
                    $('#sub_total').val(number_format.format(sub_total))
                    $(this).val(number_format.format(diskon))
                })
            }

            function diskonNominal(){
                $('#nominal_diskon').on('keyup', function(e){
                    let nominal = ($(this).val()).replace(/[^\d]/g, ""),
                        harga = ($('#harga_satuan').val()).replace(/[^\d]/g, ""),
                        qty = ($('#qty').val()).replace(/[^\d]/g, ""),
                        number_format = new Intl.NumberFormat('id-ID'),
                        sub_total = parseInt(harga) * parseInt(qty)

                    if (nominal == '') {
                        nominal = 0
                        $('#diskon').val('')
                    }
                    
                    nominal = parseInt(nominal)
                    harga = parseInt(harga)
                    if (nominal > harga) {
                        $(this).val(number_format.format(harga))
                        return
                    }

                    var diskon = (nominal/harga) * 100
                    
                    sub_total = (harga - nominal) * parseInt(qty)

                    $('#diskon').val(Math.round(diskon))
                    $('#sub_total').val(number_format.format(sub_total))
                    $(this).val(number_format.format(nominal))
                })
            }

            function qty(){
                $('#qty').on('keyup', function(){
                    let qty = ($(this).val()).replace(/[^\d]/g, ""),
                        harga = ($('#harga_satuan').val()).replace(/[^\d]/g, ""),
                        diskon = ($('#nominal_diskon').val()).replace(/[^\d]/g, ""),
                        number_format = new Intl.NumberFormat('id-ID')
                    
                    if(qty == ''){
                        qty = 1
                    }

                    var sub_total = (parseInt(harga) - parseInt(diskon)) * parseInt(qty)
                    $('#sub_total').val(number_format.format(sub_total))
                })
            }

            function hargaSatuan(){
                $('#harga_satuan').on('keyup', function(){
                    let harga = ($(this).val()).replace(/[^\d]/g, ""),
                        qty = ($('#qty').val()).replace(/[^\d]/g, ""),
                        diskon = ($('#nominal_diskon').val()).replace(/[^\d]/g, ""),
                        number_format = new Intl.NumberFormat('id-ID')
                    
                    if(qty == ''){
                        qty = 1
                    }

                    var sub_total = (parseInt(harga) - parseInt(diskon)) * parseInt(qty)
                    $('#sub_total').val(number_format.format(sub_total))
                })
            }

            function addItem(){
                $('.add-item').on('click', function(){
                    let parent = $(this).parents('.detail-produk'),
                        dataProduk = parent.find('input')

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
                         calculate()                        
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
                    if ($('[name="pemesanan"]:checked').length) {
                        return
                    }

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
                            $(`tr#${id} input[name^="harga_satuan"]`).val($('#harga_satuan').val())
                            $(`tr#${id} input[name^="nominal_diskon"]`).val($('#nominal_diskon').val())
                        }

                        editRow()
                        $('.edit-item').addClass('hidden')
                        $('.add-item').removeClass('hidden')
                        
                        $.each(dataProduk, function(key, input){
                            $(input).val('')
                        })
                        calculate()
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
                    dataPrepend = buildRow(data)

                if (findRow.length) {
                    let cb = function(){
                        findRow.remove()
                        target.prepend(dataPrepend)

                        calculate()
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
                    target.prepend(dataPrepend)
                    calculate()
                    $.each(dataProduk, function(key, input){
                        $(input).val('')
                    })
                }
            }

            function buildRow(data){
                return `<tr id="${data['kode_produk']}" class="cursor-pointer select-item">
                        <td data-key="kode_produk">${data['kode_produk']}</td>
                        <td data-key="nama_produk">${data['nama_produk']}</td>
                        <td class="text-right" data-key="harga_satuan">${data['harga_satuan']}</td>
                        <td class="text-right hidden" data-key="diskon">${data['diskon']}</td>
                        <td class="text-right hidden" data-key="stok">${data['stok']}</td>
                        <td class="text-right" data-key="nominal_diskon">${data['nominal_diskon']}</td>
                        <td class="text-center" data-key="qty">${data['qty']}</td>
                        <td class="text-right" data-key="sub_total">${data['sub_total']}</td>
                        <td class="text-center"><button class="btn btn-sm btn-danger remove-item" data-name="${data['nama_produk']}" type="button">X</button></td>
                        <input type="hidden" name="qty[${data['id_produk']}]" value="${data['qty']}">
                        <input type="hidden" name="harga_satuan[${data['id_produk']}]" value="${data['harga_satuan']}">
                        <input type="hidden" name="nominal_diskon[${data['id_produk']}]" value="${data['nominal_diskon']}">
                    </tr>`
            }

            function calculate(){
                let rows = $('#table-item tbody').children()
                if (rows.length) {
                    let total = 0,
                        number_format = new Intl.NumberFormat('id-ID'),
                        ppn = $('#ppn').text().replace(/[^\d]/g, "");
                    $.each(rows, function(key, row){
                        var sub_total = $(row).children('td[data-key="sub_total"]').text()

                        total += parseInt(sub_total.replace(/[^\d]/g, ""))
                    })

                    let grand_total = total + parseInt(ppn)
                    $('#total').text(number_format.format(total))
                    $('#grand_total').text(number_format.format(grand_total))
                }else{
                    $('#total').text('0')
                    $('#grand_total').text('0')
                }
            }
        }()
    </script>
@endpush