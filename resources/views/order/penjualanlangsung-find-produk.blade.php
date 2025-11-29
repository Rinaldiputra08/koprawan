<div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">List Produk</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">Jenis</label>
                        <div class="demo-inline-spacing radio-jenis-produk">
                            <div class="custom-control custom-radio">
                                <input type="radio" name="jenis"
                                    id="aktif" value="koperasi" class="custom-control-input" checked>
                                <label for="aktif" class="custom-control-label">Koperasi</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" name="jenis"
                                    id="non-aktif" value="titipan" class="custom-control-input">
                                <label for="non-aktif" class="custom-control-label">Titipan</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <input type="text" name="search" data-url="{{ url('penjualan/penjualan-langsung/cari-produk') }}" placeholder="Cari produk" class="form-control">
                </div>
                <div class="col-12 table-responsive mt-1">
                    <table class="table table-bordered table-stripped" id="list-produk">
                        <thead>
                            <tr>
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th>Harga Jual</th>
                                <th>Diskon</th>
                                <th>Sisa Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                            <tr data-dismiss="modal" class="cursor-pointer">
                                <td class="hidden" data-target="id_produk">{{ $item->id }}</td>
                                <td class="hidden" data-target="jenis">koperasi</td>
                                <td data-target="kode_produk">{{ $item->kode }}</td>
                                <td data-target="nama_produk">{{ $item->nama }}</td>
                                <td data-target="harga_satuan">{{ $item->harga_jual_formatted }}</td>
                                <td data-target="diskon">{{ $item->diskon->nominal_formatted ?? 0}}</td>
                                <td data-target="stok">{{ $item->stock_free }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>