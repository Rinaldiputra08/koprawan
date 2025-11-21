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

        .card.checked-card{
            box-shadow: 0 4px 24px 0 rgba(115, 103, 240, 0.1) !important;
            border: 1px solid #7367f0 !important;
        }

        .card-voucher:not(.disabled){
            cursor: pointer !important;
        }

        .card-voucher.disabled{
            background-color: rgba(0, 0, 0, 0.1)
        }
    </style>
@endpush
@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h4 class="card-title">Penjualan Langsung</h4>
                    </div><br>
                    <div class="col-12">
                        @can('create '. request()->path())
                            <button class="btn btn-gradient-primary add" data-url="{{ route('penjualan.penjualan-langsung.create') }}" type="button">Tambah</button>
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

        var penjualanJs = function(){
            const formAction = '#form-action', datatable = 'penjualanlangsung-table'

            $('.add').on('click', function(){
                let url = $(this).data('url')
                
                ajaxAction(url, 'get', function(){
                    callSelect2()
                    bsDatePicker()
                    selectProdukByKode()
                    findProduct()
                    qty()
                    addItem()
                    editItem()
                    removeItem()
                    selectItem()
                    scanQR()
                    $('[name="scan_qr"]').focus()
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

            function scanQR(){
                $('[name="scan_qr"]').on('change', function(){
                    let code = $(this).val()
                    showLoading()
                    $.ajax({
                        url: `{{ url('penjualan/penjualan-langsung/scan-qr') }}/${code}`,
                        method: 'get',
                        success: function(response){
                            $('#detail-pembeli')
                                .removeClass('d-none')
                                .html(response)

                            let grand_total = $('#grand_total').text().replace(/[^\d]/g, "");
                            let limit = $('#sisa_saldo').text().replace(/[^\d]/g, "");
                            if(parseInt(grand_total) > parseInt(limit) && limit != ""){

                                $('#row-detail-pembeli').append('<div class="col-12" id="alert-limit"><div class="alert alert-danger"><div class="alert-body">Total belanja melebihi limit.</div></div></div>')
                            }

                            _loadFeather()
                            cardVoucher()
                            cekVoucher()
                        },
                        error: function(){
                            
                        },
                        complete: function(){
                            hideLoading();
                            $('[name="scan_qr"]').val('')
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
                                window.LaravelDataTables['penjualanlangsung-table'].ajax.reload();
                            }
                            toastr[result.status](result.message, {
                                closeButton: true,
                                tapToDismiss: false
                            });
                        }, data)
                    }
    
                    let replaceOptions = {
                        title: 'Batalkan?',

                        text: 'Penjualan akan dibatalkan dan tidak bisa dikembalikan',
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
                    let url = `{{ url('penjualan/penjualan-langsung/cari-produk') }}`
                    ajaxFind(url, 'get', function(){
                        selectProduk()
                        jenisProduk()
                        search('#list-produk tbody')
                    })
                })
            }


            function search(target){
                $('[name="search"]').on('keyup', debounce(function(){
                    var jenis = $('[name="jenis"]:checked').val()
                    $.ajax({
                        url: $(this).data('url'),
                        data: {
                            search: $(this).val(),
                            jenis
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

            function selectProduk(){
                $('#list-produk tbody').on('click', 'tr', function(e){
                    let rows = $(this).children(),
                        number_format = new Intl.NumberFormat('id-ID'),
                        harga_satuan_produk,
                        diskon_promo

                    $.each(rows, function(key, row){
                        var rowObject = $(row),
                            target = rowObject.data('target'),
                            text = rowObject.text()

                        $(`#${target}`).val(text)
                        if (target == 'harga_satuan') {
                            harga_satuan_produk = text.replace(/[^\d]/g, "")
                        }else if (target == 'diskon') {
                            diskon_promo = text.replace(/[^\d]/g, "")
                        }
                    })

                    var sub_total = parseInt(harga_satuan_produk) - parseInt(diskon_promo)
                    $('#sub_total').val(number_format.format(sub_total))

                    $('#qty').val(1)

                    $('.is-invalid').removeClass('is-invalid')
                    $('.invalid-feedback').remove()
                })
            }

            function qty(){
                $('#qty').on('keyup', function(){
                    let qty = ($(this).val()).replace(/[^\d]/g, ""),
                        harga = ($('#harga_satuan').val()).replace(/[^\d]/g, ""),
                        diskon = ($('#diskon').val()).replace(/[^\d]/g, ""),
                        number_format = new Intl.NumberFormat('id-ID')
                    
                    if(qty == ''){
                        qty = 1
                    }

                    var sub_total = parseInt(qty) * (parseInt(harga) - parseInt(diskon))
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
                    $('#alert-limit').remove();

                    let qty = $('#qty').val();
                    let stock = $('#stok').val();
                    let jenis = $('#jenis').val();
                     
                    
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

                    if(parseInt(qty) > parseInt(stock)){
                        
                            $('#table-item').parent().prepend('<div class="col-12" id="alert-limit"><div class="alert alert-danger"><div class="alert-body">Qty melebihi stock.</div></div></div>')

                            shouldAppend = false
                        }

                    if (shouldAppend) {
                        appendRow(dataProduk)
                        cekVoucher()
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
                         cekVoucher()  
                         $('#alert-limit').remove()                      
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
                        calculate()
                        cekVoucher()
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
                        <td class="text-right hidden" data-key="stok">${data['stok']}</td>
                        <td class="text-center" data-key="qty">${data['qty']}</td>
                        <td class="text-right" data-key="diskon">${data['diskon']}</td>
                        <td class="text-right" data-key="sub_total">${data['sub_total']}</td>
                        <td class="text-center"><button class="btn btn-sm btn-danger remove-item" data-name="${data['nama_produk']}" type="button">X</button></td>
                        <input type="hidden" name="jenis[${data['jenis']}][${data['id_produk']}]" value="${data['jenis']}">
                        <input type="hidden" name="qty[${data['jenis']}][${data['id_produk']}]" value="${data['qty']}">
                        <input type="hidden" name="nominal_diskon[${data['jenis']}][${data['id_produk']}]" value="${data['nominal_diskon']}">
                    </tr>`
            }

            function calculate(){
                let rows = $('#table-item tbody').children()
                if (rows.length) {
                    let total = 0,
                        number_format = new Intl.NumberFormat('id-ID')

                    $.each(rows, function(key, row){
                        var sub_total = $(row).children('td[data-key="sub_total"]').text()
                       
                        total += parseInt(sub_total.replace(/[^\d]/g, ""))
                       
                    })

                    let grand_total = total
                    let limit = $('#sisa_saldo').text().replace(/[^\d]/g, "");

                    if(grand_total > parseInt(limit)){
                        $('#table-item tbody tr:first-child').remove();
                        $('.table-responsive').parent().prepend('<div class="col-12" id="alert-limit"><div class="alert alert-danger"><div class="alert-body">Total belanja melebihi limit.</div></div></div>')
                    }else{
                        
                        $('#total').text(number_format.format(total))
                        $('#grand_total').text(number_format.format(grand_total))
                    }
                    
                }else{
                    $('#total').text('0')
                    $('#potongan').text('0')
                    $('#grand_total').text('0')
                }
            }

            function calculateVoucher(nominal = 0){
                let list_voucher_checked = $('#list-voucher').find('.card-voucher.checked-card'),
                    // potongan = $('#potongan').text().replace(/[^\d]/g, ""),
                    number_format = new Intl.NumberFormat('id-ID'),
                    total = $('#total').text().replace(/[^\d]/g, "")
                    
                // total = parseInt(total)
                // potongan = parseInt(potongan) + nominal

                // let grand_total = parseInt(total) - potongan

                // if (grand_total < 0) {
                //     grand_total = 0
                //     potongan = total
                // }else{

                // }

                var potongan = 0
                $.each(list_voucher_checked, function(i, voucher){
                    nominal = $(voucher).data('nominal')
                    potongan += parseInt(nominal)
                })

                let grand_total = parseInt(total) - potongan
                if (grand_total < 0) {
                    grand_total = 0
                    potongan = total
                }

                $('#potongan').text(number_format.format(potongan))
                $('#grand_total').text(number_format.format(grand_total))
            }

            function cardVoucher(){
                $('#list-voucher').on('click', '.card-voucher:not(.disabled)', function () {
                    let checked = $(this).hasClass('checked-card')
                        
                    if (checked) {
                        $(this).find('input[type="checkbox"]').prop('checked', false)
                        $(this).removeClass("checked-card");
                    } else {
                        $(this).find('input[type="checkbox"]').prop('checked', true)
                        $(this).addClass("checked-card");
                    }
                    
                    selectVoucher($(this))

                })
            }

            function selectVoucher(voucher){
                let isChecked = voucher.hasClass('checked-card'),
                    nominal = parseInt(voucher.data('nominal'))

                if (!isChecked) {
                    nominal *= -1
                }

                calculateVoucher(nominal)
            }

            function cekVoucher(){
                let vouchers = $('#list-voucher').find('.card-voucher')
                $.each(vouchers, function(key, voucher){
                    voucher = $(voucher)

                    var kriteria = voucher.find('#kriteria-voucher'),
                        isCheckedBefore = voucher.hasClass('checked-card')

                    if (kriteria.length) {
                        kriteria = kriteria.find('li')
                        
                        var has_minimal = false;
                        $.each(kriteria, function(index, list){
                            var nama_kriteria = $(list).data('kriteria'),
                                nominal = $(list).data('nominal')

                            if (nama_kriteria == 'minimal total belanja') {
                                var total_belanja = $('#total').text().replace(/[^\d]/g, "");

                                if (parseInt(total_belanja) >= parseInt(nominal)) {
                                    voucher.removeClass('disabled')
                                } else {
                                    voucher
                                        .addClass('disabled')
                                        .removeClass('checked-card')

                                    // if (isCheckedBefore) {
                                    //     selectVoucher(voucher)
                                    //     console.log(voucher);
                                    // }
                                }

                                has_minimal = true;
                            }
                            if (isCheckedBefore) {
                                selectVoucher(voucher)
                            }
                        })

                        if (!has_minimal) {
                            voucher.removeClass('disabled')
                        }
                    } else {
                        voucher.removeClass('disabled')
                    }
                })
            }

            function jenisProduk(){
                $('.radio-jenis-produk').on('change', function(event){
                    var jenis = event.target.value
                    $.ajax({
                        url : `{{ url('penjualan/penjualan-langsung/cari-produk') }}?jenis=${jenis}`,
                        method: 'GET',
                         success: function(result){
                            $('#list-produk tbody').html(result)
                        },
                        error: function(e){
                           const error = e.responseJSON?.message

                        }

                    })
                })
            }
        }()
    </script>
@endpush