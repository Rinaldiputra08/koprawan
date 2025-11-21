<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Retur Penjualan Langsung</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row mt-1">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-3">
                            <img src="{{ asset('') }}assets/images/logo/lambang.png" height="80">
                        </div>
                        <div class="col-9">
                                <h5 class="font-weight-bolder">Honda Bintaro</h5>
                                <p class="font-small-3">CBD 03 dan 05, Blok A2, Kota Taman Bintaro Jaya Sektor VII, Pondok Aren, Pd. Jaya, Kec. Tangerang, Tangerang Selatan, Banten 15224</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-5">Nomor Pesanan</div>
                        <div class="col-1">:</div>
                        <div class="col-6"><strong>{{$data->penjualan->nomor}}</strong></div>
                        <div class="col-5">Nama</div>
                        <div class="col-1">:</div>
                        <div class="col-6"><strong>{{$data->penjualan->karyawan->nama}}</strong></div>
                        <div class="col-5">Divisi</div>
                        <div class="col-1">:</div>
                        <div class="col-6">{{$data->penjualan->karyawan->divisi}}</div>
                        <div class="col-5">Tanggal Pembelian</div>
                        <div class="col-1">:</div>
                        <div class="col-6">{{$data->penjualan->tanggal_formatted}}</div>     
                    </div>
                </div>
            </div>
              <div class="row mt-2">
                <div class="col-12 table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th width="5" class="text-center">No</th>
                                <th>Kode</th>
                                <th>Nama Produk</th>
                                <th class="text-center">Qty</th>
                                <th class="text-right">Harga</th>
                                <th class="text-right">Diskon</th>
                                <th class="text-right">Sub Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data->penjualan->penjualanDetail as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $item->produk->kode }}</td>
                                <td>{{ $item->produk->nama }}</td>
                                <td class="text-center">{{ $item->qty }}</td>
                                <td class="text-right">{{ $item->produk->harga_jual_formatted }}</td>
                                <td class="text-right">{{ $item->nominal_diskon}}</td>
                                <td class="text-right">{{ $item->grand_total_formatted }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                                <tr>
                                    <th colspan="5" rowspan="3" class="border-0 bg-transparent">
                                    <th>Total</th>
                                    <th class="text-right">{{ $data->total_formatted }}</th>
                                </tr>
                                <tr>
                                    <th>Potongan</th>
                                    <th class="text-right">{{ $data->diskon_formatted }}</th>
                                </tr>
                                <tr>
                                    <th>Grand Total</th>
                                    <th class="text-right">{{ $data->grand_total_formatted }}</th>
                                </tr>
                            </tfoot>
                            
                    </table>

                    <div class="row">
                        <div class="col-md-6">                
                            <div class="border border-danger p-1 font-small-3">
                                Keterangan retur: <strong>{{ $data->keterangan }}</strong>
                                <p>{{ $data->keterangan_batal }}</p>
                                <p>{{ $data->tanggal_batal }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 font-small-3 text-right">
                            <p class="mb-0">Dibuat oleh {{ $data->user_input }}</p>
                            <span>{{ $data->tanggal }}</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-primary" type="button" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
    </div>
</div>