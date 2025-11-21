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
                        <h4 class="card-title">Produk Titipan</h4>
                    </div><br />
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
        
        var titipanJs = function() {
            const formAction = '#form-action',
            datatable = 'titipan-table'

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

            $('#'+datatable).on('click', '.action', function(e) {
                e.preventDefault()
                let url = this.getAttribute('href');
                let method = $(this).data('method');
                ajaxAction(url, method, function(){
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
