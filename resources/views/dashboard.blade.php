@extends ('layout.master')
@push('css')
<link rel="stylesheet" href="{{ asset('') }}assets/vendors/css/charts/apexcharts.css">
<style>
    .chart-outstanding{
        position: relative;
        height: 300px !important;
    }
</style>
@endpush
@section('content')
<!-- Dashboard -->
<section id="dashboard-ecommerce">
    <div class="row match-height">
        <div class="col-xl-4 col-md-6 col-12">
            <div class="card card-congratulations">
                <div class="card-body text-center">
                    <img src="{{ asset('') }}assets/images/elements/decore-left.png" class="congratulations-img-left" alt="card-img-left" />
                    <img src="{{ asset('') }}assets/images/elements/decore-right.png" class="congratulations-img-right" alt="card-img-right" />
                    <div class="avatar avatar-xl bg-primary shadow">
                        <div class="avatar-content">
                            <img src="{{ asset('') }}storage/images/profile/small_{{ $user->foto }}" alt="" class="round">
                        </div>
                    </div>
                    <div class="text-center">
                        <h1 class="mb-1 text-white text-capitalize">Selamat Datang {{ $user->name }},</h1>
                        <p class="card-text m-auto w-75">
                            Tetap <strong>semangat!</strong>. Semoga harimu menyenangkan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Dashboard end-->
@endsection
@push('vendor')
@endpush
@push('js')
<script src="{{ asset('') }}assets/vendors/js/charts/chart.min.js"></script>
<script src="{{ asset('') }}assets/vendors/js/charts/chartjs-plugin-datalabels.min.js"></script>
<script src="{{ asset('') }}assets/vendors/js/charts/apexcharts.min.js"></script>
<script>
    'use strict'

    
</script>
@endpush
