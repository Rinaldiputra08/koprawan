<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Detail Berita Acara Gudang</h4>
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
                    <div class="row mt-2">
                        <div class="col-12">
                            <h5 class="font-weight-bolder">Nomor : {{ $data->nomor }} / {{ $data->tanggal_berita_acara_formatted }}</h5>
                            <h5 class="font-weight-bolder">Jenis : {{ $data->jenis }}
                        </div>
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
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data->beritaAcaraGudangDetail as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $item->produk->kode }}</td>
                                <td>{{ $item->produk->nama }}</td>
                                <td class="text-center">{{ $item->qty }}</td>
                            </tr>
                            @endforeach
                        </tbody>
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
                    @endif
                </div>
                <div class="col-md-6 font-small-3 text-right">
                    <p class="mb-0">Dibuat oleh {{ $data->user_input }}</p>
                    <span>{{ $data->tanggal }}</span>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            @if (!$data->tanggal_batal)
            <a href="{{ route('gudang.berita-acara-gudang.batal', $data->id) }}" data-method="PUT" class="btn btn-danger batal">Batal</a>
            @endif
            <button class="btn btn-outline-primary" type="button" data-dismiss="modal">Tutup</button>
        </div>
    </div>
</div>