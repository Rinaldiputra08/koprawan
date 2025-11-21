<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Detail Pemesanan Produk</h4>
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
                        <div class="col-5">Supplier</div>
                        <div class="col-1">:</div>
                        <div class="col-6"><strong>{{ $data->supplier->nama }}</strong></div>
                        <div class="col-5">Nomor Telp.</div>
                        <div class="col-1">:</div>
                        <div class="col-6">{{ $data->supplier->nomor_telepon }}</div>
                        <div class="col-5">Alamat</div>
                        <div class="col-1">:</div>
                        <div class="col-6">{{ $data->supplier->alamat }}</div>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12">
                    <h5 class="font-weight-bolder">Nomor : {{ $data->nomor }} / {{ $data->tanggal_pemesanan_formatted }}</h5>
                </div>
                <div class="col-12 table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th width="5" class="text-center">No</th>
                                <th>Kode</th>
                                <th>Nama Produk</th>
                                <th class="text-center">Penerimaan</th>
                                <th class="text-center">Qty</th>
                                <th class="text-right">Harga</th>
                                <th class="text-right">Diskon</th>
                                <th class="text-right">Sub Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data->pemesananDetail as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $item->produk->kode }}</td>
                                <td>{{ $item->produk->nama }}</td>
                                <td class="text-center">
                                    @if ($item->penerimaan)
                                    <span class="text-success font-small-3">Sudah</span>
                                    @else
                                    <span class="text-warning font-small-3">Belum</span>
                                    @endif
                                </td>
                                <td class="text-center">{{ $item->qty }}</td>
                                <td class="text-right">{{ $item->produk->harga_beli_formatted }}</td>
                                <td class="text-right">{{ $item->diskon_formatted }}</td>
                                <td class="text-right">{{ $item->sub_total_formatted }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="6" rowspan="3" class="border-0 bg-transparent">
                                    @if ($data->keterangan)
                                    <strong>Note : </strong>
                                    <p class="text-capitalize">{{ $data->keterangan }}</p>
                                    @endif
                                </th>
                                <th>Total</th>
                                <th class="text-right">{{ $data->total_formatted }}</th>
                            </tr>
                            <tr>
                                <th>PPN</th>
                                <th class="text-right">{{ $data->ppn_formatted }}</th>
                            </tr>
                            <tr>
                                <th>Grand Total</th>
                                <th class="text-right">{{ numberFormat($data->total + $data->ppn) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    @if ($data->tanggal_batal)
                    <div class="border border-danger p-1 font-small-3">
                        Dibatalkan oleh <strong>{{ $data->user_batal }}</strong>
                        <p>{{ $data->keterangan_batal }}</p>
                        <p>{{ $data->tanggal_batal }}</p>
                    </div>
                    @elseif($data->pemesanan_detail_count == 0)
                    <div class="alert alert-danger">
                        <div class="alert-body">
                            Belum penerimaan
                        </div>
                    </div>
                    @endif
                </div>
                <div class="col-md-6 font-small-3 text-right">
                    <p class="mb-0">Dibuat oleh {{ $data->user_input }}</p>
                    <span>{{ $data->tanggal }}</span>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            @if (!$data->tanggal_batal and $data->pemesanan_detail_count == 0)
            <a href="{{ route('pembelian.pemesanan-produk.batal', $data->id) }}" data-method="PUT" class="btn btn-danger batal">Batal</a>
            @endif
            <button class="btn btn-outline-primary" type="button" data-dismiss="modal">Tutup</button>
        </div>
    </div>
</div>