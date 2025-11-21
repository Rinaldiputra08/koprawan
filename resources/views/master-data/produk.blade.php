@extends ('layout.master')
@push('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/vendors/css/extensions/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/vendors/css/forms/select/select2.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/vendors/css/croppie/croppie.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/vendors/css/extensions/swiper.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/css/plugins/extensions/ext-component-swiper.min.css">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/vendors/css/lightbox/lightbox.css"> --}}
    <link href ="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css" rel = "stylesheet" crossorigin="anonymous">
    
    <style>
        .form-control.datepicker{
            padding: .438rem 1rem !important;
        }

        .bs-stepper .bs-stepper-header .step.active .step-trigger:disabled{
            opacity: 1 !important;
        }

        .bs-stepper .step-trigger:not(:disabled):not(.disabled){
            cursor: default !important;
            pointer-events: none !important;
        }

        .bs-stepper .bs-stepper-header .step:not(.active){
            opacity: .65 !important;
        }

        .thumbnail-container .btn-remove{
            padding: .5rem .5rem !important;
            position: relative !important;
            left: -36px;
            top: -40px;
        }

        .croppie-container .cr-boundary{
            margin: 0 !important;
        }

        .croppie-container .cr-slider-wrap{
            margin-left: 0 !important;
            margin-bottom: .5rem !important;
            margin-top: .5rem !important;
            text-align: start;
        }

        .thumbnail-container{
            margin-right: -20px;
        }
        .thumbnail-container img{
                height: 100px !important;
                width: 100px !important;
            }

        .swiper-centered-slides.swiper-container .swiper-slide img{
            border-radius: 0.357rem !important
        }
        .foto-thumbnail {
            margin-top: -25px;
        }

        @media(max-width: 768px){
            .croppie-container .cr-slider-wrap{
                width: 100% !important;
            }

            .croppie-container .cr-boundary{
                width: 280px !important;
                height: 280px !important;
            }

            .croppie-container .cr-boundary .cr-viewport{
                width: 250px !important;
                height: 250px !important;
            }

            .avatar-group img,
            .thumbnail-container img{
                height: 180px !important;
                width: 180px !important;
            }

            .thumbnail-container{
                margin-right: 0 !important;
            }

            .thumbnail-container .btn-remove{
                /* left: 240px !important; */
                top: -80px !important;
            }
        }
    </style>
@endpush
@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h4 class="card-title">Produk</h4>
                    </div><br />
                    <div class="col-md-4">
                        @can('create '.request()->segment(1).'/'.request()->segment(2))
                            <button type="button" data-url="{{ route('master-data.produk.create') }}" class="btn btn-gradient-primary add">Tambah</button>
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
    <script src="{{ asset('') }}assets/vendors/js/croppie/croppie.js"></script>
    <script src="{{ asset('') }}assets/vendors/js/extensions/swiper-7.4.1.min.js"></script>
    <script src="{{ asset('') }}assets/vendors/js/lightbox/lightbox.js"></script>
    <script src = "https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.js" crossorigin="anonymous"></script>
    
    @endpush
    @push('js')
    
    {!! $dataTable->scripts() !!}
    <script>
        'use strict'
        
        var produkJs = function() {
            const formAction = '#form-action',
            datatable = 'produk-table'

           function swiper(){
               var mySwiperOpt1 = new Swiper('.swiper-centered-slides', {
                   slidesPerView: 'auto',
                   centeredSlides: true,
                   spaceBetween: 30,
                   navigation: {
                       nextEl: '.swiper-button-next',
                       prevEl: '.swiper-button-prev'
                    }
                });
                console.log(mySwiperOpt1);
            }

           
           function lightbox(){
               $(document).on("click", '[data-toggle="lightbox"]', function(event){
                    event.preventDefault();
                    $(this).ekkoLightbox();
                });
           }

            function changePhoto(){
                var croppicture = $('.picture-canvas').croppie({
                    enableExif: true,
                    viewport:{
                        width: 500,
                        height: 500,
                        type: 'square'
                    },
                    enableOrientation: true,
                    boundary: {
                        width: 550,
                        height: 550
                    }
                })

                $('#change-picture').on('change', function(e){
                    let picturename = $(this).val(),
                        file_extension = picturename.split('.').pop(),
                        allowed_extension = ['jpg', 'jpeg', 'JPG', 'JPEG']

                    if(picturename == '') return false;

                    if(!allowed_extension.includes(file_extension)){
                        e.preventDefault()
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops..',
                            text: 'Format file pilih salah, pilih gambar dengan ekstensi jpg atau jpeg'
                        })
                        return
                    }

                    $('.avatar-group').addClass('d-none')
                    $('.croppie-group').removeClass('d-none')
                    $('.label-button').text('Ganti Foto')
                    $('.rotate-button').removeClass('d-none')

                    let reader = new FileReader()

                    reader.onload = function(event){
                        croppicture.croppie('bind',{
                            url: event.target.result
                        })
                    }
                    reader.readAsDataURL(this.files[0])
                })

                $('.rotate').on('click', function(){
                    croppicture.croppie('rotate', parseInt($(this).data('deg')))
                })

                $('.crop').on('click', function(){
                    croppicture.croppie('result', {
                        type: 'canvas',
                        format: 'jpeg',
                        size: {
                            width: 800,
                            height: 800,
                            type: 'square'
                        }
                    }).then(function(res){
                        let id_checkbox = Math.random()
                        $('.delivery-avatar').attr('src', res)
                        let thumbnail = $('body').find('.thumbnail-container').length
                        $('.thumbnail-result .row')
                            .append(`<div class="thumbnail-container">
                                <img src="${res}" alt="" class="rounded mb-1">
                                <a class="btn btn-xs btn-danger btn-remove" onclick="produkJs.removePhoto(this)"><i data-feather='trash-2'></i></a>
                                <input type="hidden" name="upload_foto[]" value="${res}"/>
                                <div class="custom-control custom-checkbox foto-thumbnail">
                                        <input type="checkbox" name="foto_thumbnail[]" id="${id_checkbox}" value="${thumbnail}" class="custom-control-input">
                                        <label for="${id_checkbox}" class="custom-control-label"></label>
                                    </div>
                                </div>`)

                        $('.label-button').text('Tambah Foto')
                        $('.avatar-group').removeClass('d-none')
                        $('.error-upload').addClass('d-none')
                        $('.croppie-group').addClass('d-none')
                        $('.rotate-button').addClass('d-none')
                        $('.error').remove()
                        _loadFeather()
                    })
                })
            }

            function removePhoto(btn){
                $(btn).parent().remove()
            }
            

            function removePhotoEdit(btn)
            {
                let elm = $(btn)
                elm.parent().hide()
                elm.parent().find(`[name^="remove_upload_foto"]`).val(elm.data('id'))
            }

            $('#'+datatable).on('click', '.action', function(e) {
                e.preventDefault()
                let url = this.getAttribute('href');
                let method = $(this).data('method');
                ajaxAction(url, method, function(){
                    changePhoto()
                    callSelect2()
                    swiper()
                    lightbox()
                    storeAction(formAction, datatable)
                })
            })

            $('.add').on('click', function() {
                let url = $(this).data('url')
                ajaxAction(url, 'get', function(){
                    changePhoto()
                    swiper();
                    callSelect2()
                    storeAction(formAction, datatable);
                })
            })

            return {
                removePhoto,
                removePhotoEdit,
            }
        }()

    </script>
@endpush
