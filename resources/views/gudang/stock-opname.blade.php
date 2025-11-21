@extends('layout.master')

@push('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h4 class="card-title">Master Stock Opname</h4>
                    </div><br>
                    <div class="col-12">
                        <a href="{{ route('gudang.stock-opname') . '?Cetak'  }}" class="btn btn-gradient-primary" target="_blank">
                            Proses
                        </a>
                    </div><br>
                </div>
            </div>
        </div>
    </section>
@endsection