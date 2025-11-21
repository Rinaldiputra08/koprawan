@if ($data)
<div class="row" id="row-detail-pembeli">
    <div class="col-md-6">
        <div class="form-group" style="text-align: center">
            <img src="{{ asset('storage/images/karyawan/medium_' . $data->foto)}}" alt="" style="width: 150px; height: 170px; border-radius:7px; margin-buttom:30px;" id=""><br>
         </div>        
    </div>
    <div class="col-md-6">
        <div class="row mt-2">
            <div class="col-12">
                <table class="table table-borderless">
                    <tr>
                        <td>Nama</td>
                        <td>:</td>
                        <td>{{$data->nama}}</td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td>:</td>
                        <td>{{$data->divisi}}</td>
                    </tr>
                    <tr>
                        <td>Limit</td>
                        <td>:</td>
                        <td id="limit">{{numberFormat($data->limit->nominal)}}</td>
                    </tr>
                    <tr>
                        <td>Sisa Saldo</td>
                        <td>:</td>
                        <td id="sisa_saldo">{{numberFormat($data->limit->nominal - ($data->piutang->nominal ?? 0))}}</td>
                    </tr>
                </table>
                <input type="hidden" name="karyawan" value="{{$data->uuid}}">
            </div>
        </div>
    </div>
</div>
<div class="row" id="list-voucher"> 
    @foreach ($vouchers as $item)
    <div class="col-lg-4 col-sm-7 col-12">
            <div class="card card-voucher disabled" data-nominal="{{$item->nominal}}">
                <input type="checkbox" class="d-none" name="voucher[]" value="{{$item->id}}"/>
                <div class="card-header">
                    <div>
                        <h2 class="font-weight-bolder mb-0">{{ $item->nominal_formatted }}</h2>
                    </div>
                    <div class="avatar bg-light-primary p-50 m-0">
                        <i data-feather="tag"></i>
                    </div>
                </div>
                <div class="card-body">
                    <p class="card-text">{{ $item->nama }}</p>
                    @if ($item->ketentuan)
                    <ul class="list-unstyled text-danger" style="font-size: 10px" id="kriteria-voucher">
                        @foreach ($item->kriteria as $kriteria)
                        <li data-kriteria="{{ $kriteria->nama }}" data-nominal="{{$kriteria->nominal}}">{{ $kriteria->nama }} ({{ $kriteria->nominal }})</li>
                        @endforeach
                    </ul>                       
                    @endif
                </div>
            </div>
        </div>
    @endforeach

</div>
@else
<div class="row">
    <div class="col-12">
        <div class="alert alert-danger">
            <div class="alert-body">Data karyawan tidak ditemukan.</div>
        </div>
    </div>
</div>
@endif