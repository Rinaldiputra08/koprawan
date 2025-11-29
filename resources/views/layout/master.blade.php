<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="HBOS - Honda bintaro operasional sistem">
    <meta name="keywords" content="honda bintaro operasional sistem">
    <meta name="author" content="hondabintaro.com">
    @stack('meta')
    <title>{{ ucfirst(Request::segment(1)) . (Request::segment(2) != '' ? ' ' . ucfirst(Request::segment(2)) : '') }}
        &mdash; HBOS</title>
    <link rel="apple-touch-icon" href="{{ asset('') }}assets/images/ico/hbos.png">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('') }}assets/images/ico/hbos.png">
    {{--
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600"
        rel="stylesheet"> --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/vendors/css/extensions/toastr.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/vendors/css/pickers/pickadate/pickadate.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/vendors/css/pickers/flatpickr/flatpickr.min.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/css/bootstrap.css?version=3.0">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/css/bootstrap-extended.css?version=3.0">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/css/colors.css?version=3.0">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/css/components.css?version=3.0">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/css/plugins/extensions/ext-component-toastr.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/css/plugins/forms/pickers/form-flat-pickr.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/css/plugins/forms/pickers/form-pickadate.css">
    @stack('css')
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/css/style.css?version=3.0">
    <!-- END: Custom CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static" data-open="click"
    data-menu="vertical-menu-modern" data-col="">

    <div class="modal fade text-left modal-filter" tabindex="-1" role="dialog" data-backdrop="static"
        aria-labelledby="myModalLabel16" aria-hidden="true"></div>

    <div class="modal fade text-left modal-global" id="xlarge" tabindex="-1" role="dialog" data-backdrop="static"
        aria-labelledby="myModalLabel16" aria-hidden="true"></div>

    <div class="modal fade text-left modal-find" id="modal-small" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel16" aria-hidden="true"></div>

    {{-- loading --}}
    <div class="preload-wrapper6 overlay" align="center">
        <div class="lds-ripple">
            <div></div>
            <div></div>
        </div>
    </div>
    {{-- end loading --}}
    <!-- BEGIN: Header-->
    @include ('layout.header')
    <!-- END: Header-->

    <!-- BEGIN: Main Menu-->
    @include ('layout.sidebar')
    <!-- END: Main Menu-->
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">
                                {{ ucwords(str_replace('-', ' ', Request::segment(1))) }}
                            </h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a
                                            href="#">{{ ucwords(str_replace('-', ' ', Request::segment(1))) }}</a>
                                    </li>
                                    <li class="breadcrumb-item active">
                                        {{ ucwords(str_replace('-', ' ', Request::segment(2))) }}
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>
    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light">
        <p class="clearfix mb-0"><span class="float-md-left d-block d-md-inline-block mt-25">COPYRIGHT &copy;
                {{ date('Y') }}<a class="ml-25" href="https://honda-bintaro.com" target="_blank">KOPRAWAN
                </a><span class="d-none d-sm-inline-block">, All rights Reserved</span></span><span
                class="float-md-right d-none d-md-block">Hand-crafted & Made with<i data-feather="heart"></i></span></p>
    </footer>
    <button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
    <!-- END: Footer-->

    <!-- BEGIN: Vendor JS-->
    <script src="{{ asset('') }}assets/vendors/js/vendors.min.js"></script>
    <script src="{{ asset('') }}assets/vendors/js/extensions/toastr.min.js"></script>
    <script src="{{ asset('') }}assets/js/scripts/components/components-modals.js"></script>

    <script src="{{ asset('') }}assets/vendors/js/pickers/pickadate/picker.js"></script>
    <script src="{{ asset('') }}assets/vendors/js/pickers/pickadate/picker.date.js"></script>
    <script src="{{ asset('') }}assets/vendors/js/pickers/pickadate/picker.time.js"></script>
    <script src="{{ asset('') }}assets/vendors/js/pickers/pickadate/legacy.js"></script>
    <script src="{{ asset('') }}assets/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
    {{--
    <script src="{{ asset('') }}assets/vendors/js/idlejs/idle.js"></script> --}}
    <script src="{{ asset('') }}assets/js/scripts/forms/pickers/form-pickers.js"></script>
    <script src="{{ asset('') }}assets/js/scripts/components/components-tooltips.js"></script>
    @stack('vendor')
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="{{ asset('') }}assets/js/core/app-menu.js"></script>
    <script src="{{ asset('') }}assets/js/core/app.js"></script>
    <!-- END: Theme JS-->

    <script>
        // idle({
        //     onIdle: function (){
        //         window.location.href = "{{ route('dashboard.index') }}"
        //     },
        //     idle: 2 * (1000 * 60 * 60)
        // }).start();

        function handleNotification() {
            $.ajax({
                method: "GET",
                url: "{{ route('notification') }}",
                success: function (result) {
                    $('#badge-notification').html(result.count)
                    $('#list-notification').html(result.data)
                },
                error: function (e) {
                    const errors = e.responseJSON?.message;
                    if (errors) {
                        callModalError(errors);
                    }
                }
            })
        }

        $('.login-as').on('click', function () {
            $.ajax({
                method: 'get',
                url: `{{ route('login-as') }}`,
                success: function (res) {
                    callModalFilter(res)
                }
            })
        })

        handleNotification()

        function handleOtherNotification() {
            $.ajax({
                method: 'get',
                url: '{{ route("notification.other") }}',
                success: function (res) {
                    $('#badge-leads').html(res.count)
                    $('#list-leads').html(res.data)
                    _loadFeather()
                },
                error: function (e) {
                    const errors = e.responseJSON?.message
                    if (errors) callModalError(errors)
                }
            })

        }

        handleOtherNotification()

        _loadFeather();
        // datePicker();

        /// CEK UNTUK 2 MODAL SUPAYA BISA SCROLL
        $('body').on('hidden.bs.modal', function () {
            if ($('.modal.show').length > 0) {
                $('body').addClass('modal-open');
            }
        });

        function _loadFeather() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        }

        function datePicker() {
            var basicPickr = $('.flatpickr-basic'),
                rangePickr = $('.flatpickr-range'),
                dateTimePickr = $('.flatpickr-date-time');

            if (basicPickr.length) {
                basicPickr.flatpickr();
            }

            if (rangePickr.length) {
                rangePickr.flatpickr({
                    mode: 'range'
                });
            }

            if (dateTimePickr.length) {
                dateTimePickr.flatpickr({
                    enableTime: true,
                    time_24hr: true
                })
            }
        }

        function showLoading() {
            $(".preload-wrapper6").show();
        }

        function hideLoading() {
            $(".preload-wrapper6").fadeOut('fast');
        }

        function saveLoading(type = 'show', mess = 'Processing...') {
            const btnSave = $('.btn-save')
            if (type == 'show') {
                btnSave.attr('disabled', 'true');
                btnSave.html(mess);
            } else {
                setTimeout(() => {
                    btnSave.removeAttr('disabled');
                    btnSave.html(mess);
                }, 1000);
            }
        }

        function storeAction(formId, dataTable, cb = null) {
            const _form = $(formId)
            _form.on('submit', function (e) {
                e.preventDefault()

                // let formdata = _form.serialize();
                let formdata = new FormData(this)
                let url = this.getAttribute('action')
                let method = this.getAttribute('method')

                saveLoading()
                $.ajax({
                    method,
                    headers: {
                        'Accept': 'application/json, text-plain, */*',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url,
                    data: formdata,
                    processData: false,
                    contentType: false,
                    success: function (result) {
                        if (result.status == 'success') {
                            $('.modal-global').modal('hide');
                            window.LaravelDataTables[dataTable].ajax.reload();
                        }
                        toastr[result.status](result.message, {
                            closeButton: true,
                            tapToDismiss: false
                        });
                        if (cb) {
                            cb()
                        }
                    },
                    error: function (e) {
                        _form.find('span.error').remove()
                        _form.find('.is-invalid').removeClass('is-invalid')
                        const errors = e.responseJSON?.errors;
                        if (errors) {
                            let i = 0
                            for (const [key, value] of Object.entries(errors)) {
                                if (i == 0) {
                                    $(`[name="${key}"]`).focus()
                                }
                                i++;
                                _form.find(`[name^='${key}']`).addClass('is-invalid').parents('.form-group').append(`<span class="error">${value}</span>`)
                            }
                        }
                    },
                    complete: function () {
                        saveLoading('', 'Simpan')
                    }
                })

            })
        }

        function callSelect2() {
            const selectTwo = $('.select2').select2({
                placeholder: $(this).data('placeholder')
            })
        }

        function bsDatePicker(selector = '.datepicker', options = null, event = null, callback = null) {
            let datePicker
            if (options) {
                datePicker = $(selector).datepicker(options)
            } else {
                datePicker = $(selector).datepicker({
                    format: "dd-mm-yyyy",
                    autoclose: true,
                    todayHighlight: true,
                    zIndexOffset: true
                })
            }

            if (event) {
                datePicker.on(event, callback)
            }
        }

        function ajaxAction(url, method, cb, data = null) {
            showLoading();
            $.ajax({
                method,
                url,
                data,
                success: function (result) {
                    if (method.toLowerCase() == 'get') {
                        callModal(result)
                        _loadFeather()
                    }
                    cb(result)
                }, error: function (e) {
                    const errors = e.responseJSON?.message;
                    if (errors) {
                        callModalError(errors)
                    }
                },
                complete: function () {
                    hideLoading()
                }
            })
        }

        function ajaxFind(url, method, cb) {
            showLoading()
            $.ajax({
                method,
                url,
                success: function (result) {
                    callModalFind(result)
                    _loadFeather()
                    cb()
                },
                error: function (e) {
                    const errors = e.responseJSON?.message
                    if (errors) {
                        callModalError(errors)
                    }
                },
                complete: function () {
                    hideLoading()
                }
            })
        }

        function ajaxSelect(url, method, cb, data = null) {
            $.ajax({
                method,
                url,
                data,
                success: function (result) {
                    cb(result)
                },
                error: function (e) {
                    const error = e.responseJSON?.message
                    if (error) {
                        toastr[result.status](result.message, {
                            closeButton: true,
                            tapToDismiss: false
                        });
                    }
                }
            })
        }

        function formatAngka(nilai) {
            bk = nilai.replace(/[^\d]/g, "");
            ck = "";
            panjangk = bk.length;
            j = 0;
            for (i = panjangk; i > 0; i--) {
                j = j + 1;
                if (((j % 3) == 1) && (j != 1)) {
                    ck = bk.substr(i - 1, 1) + "." + ck;
                    xk = bk;
                } else {
                    ck = bk.substr(i - 1, 1) + ck;
                    xk = bk;
                }
            }
            return ck;

        }

        function debounce(callback, ms) {
            var timer = 0;
            return function () {
                var context = this,
                    args = arguments;
                clearTimeout(timer);
                timer = setTimeout(function () {
                    callback.apply(context, args);
                }, ms || 800);
            };
        }

        function callModal(element) {
            const modal = $(".modal-global")
            modal.html(element);
            modal.modal('show');
        }

        function callModalFilter(element) {
            const modal = $('.modal-filter')
            modal.html(element)
            modal.modal('show')
        }

        function callModalError(message) {
            const modal = $(".modal-global")
            modal.html(`<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel16">Error</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                    <div class="modal-body">
                                        <div class="text-center text-danger py-5">
                                        ${message}
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-primary waves-effect" data-dismiss="modal">Tutup</button>
                                    </div>
                            </div>
                        </div>`);
            modal.modal('show');

        }

        function callModalFind(element) {
            const modalFind = $(".modal-find")
            modalFind.html(element);
            modalFind.modal('show');
        }

        function confirmation(cb, replaceOptions = null, dismissedCb = null) {
            let options = {
                title: 'Yakin hapus data ?',
                text: 'Data yang sudah dihapus, tidak bisa dikembalikan',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus ini!',
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-outline-danger ml-1'
                },
                buttonsStyling: false
            }

            if (replaceOptions) {
                $.each(replaceOptions, function (key, value) {
                    options[key] = value
                })
            }

            Swal.fire(options).then(function (result) {
                if (result.value) {
                    cb(result.value)
                }

                if (dismissedCb) {
                    dismissedCb()
                }
            });
        }

        // call this before showing SweetAlert:
        function fixBootstrapModal(selector) {
            var modalNode = document.querySelector(selector);
            if (!modalNode) return;

            modalNode.removeAttribute('tabindex');
            modalNode.classList.add('js-swal-fixed');
        }

        // call this before hiding SweetAlert (inside done callback):
        function restoreBootstrapModal() {
            var modalNode = document.querySelector('.modal.js-swal-fixed');
            if (!modalNode) return;

            modalNode.setAttribute('tabindex', '-1');
            modalNode.classList.remove('js-swal-fixed');
        }

    </script>
    @stack('js')
</body>
<!-- END: Body-->

</html>