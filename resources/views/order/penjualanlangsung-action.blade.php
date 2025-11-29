<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">{{ $data->id ? 'Edit Penjualan Langsung' : 'Tambah Penjualan Langsung' }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form
            action="{{ $data->id ? route('penjualan.penjualan-langsung.update', $data->id) : route('penjualan.penjualan-langsung.store') }}"
            method="POST" id="form-action">
            @csrf
            @if ($data->id) @method('put') @endif

            <div class="modal-body">


                <div class="row">
                    <div class="col-12">
                        <fieldset>
                            <legend>Data Pembeli</legend>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" name="nik_karyawan" autocomplete="off"
                                            placeholder="Input NIK Karyawan" class="form-control">
                                    </div>
                                </div>
                                <div class="col-12 d-none" id="detail-pembeli">
                                </div>
                            </div>
                        </fieldset>
                    </div>

                    <div class="col-12">
                        <fieldset>
                            <legend>Daftar Produk</legend>
                            <div class="row detail-produk">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Kode Produk</label>
                                        <input type="hidden" id="id_produk">
                                        <input type="text" id="kode_produk" class="form-control">
                                        <input type="hidden" id="jenis">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="">Nama Produk</label>
                                        <input type="text" id="nama_produk" disabled class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Harga Satuan</label>
                                        <input type="text" id="harga_satuan" disabled class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Qty</label>
                                        <input type="number" min="1" value="1" id="qty" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Sisa Stok</label>
                                        <input type="text" id="stok" disabled class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Diskon</label>
                                        <input type="text" id="diskon" disabled class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Sub Total</label>
                                        <input type="text" id="sub_total" disabled class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div style="text-align: right">
                                            <button class="btn btn-primary add-item" type="button">Tambah</button>
                                            <button class="btn btn-primary mr-1 edit-item hidden"
                                                type="button">Edit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-12 table-responsive">
                                    <table class="table table-sm table-bordered table-hover table-stripped"
                                        id="table-item">
                                        <thead>
                                            <tr>
                                                <th>Kode Produk</th>
                                                <th>Nama Produk</th>
                                                <th class="text-right">Harga</th>
                                                <th class="text-center">Qty</th>
                                                <th class="text-center">Diskon</th>
                                                <th class="text-right">Sub Total</th>
                                                <th class="text-center">Hapus</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-md-7">

                                </div>
                                <div class="col-md-5">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th>Total</th>
                                            <th class="text-right" id="total">0</th>
                                        </tr>
                                        <tr class="border-bottom">
                                            <th>Potongan</th>
                                            <th class="text-right" id="potongan">0</th>
                                        </tr>
                                        <tr>
                                            <th>Grand Total</th>
                                            <th class="text-right" id="grand_total">0</th>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">

                <div class="float-right">
                    <button class="btn btn-primary btn-save" type="submit">Simpan</button>
                    <button class="btn btn-outline-primary waves-effect" type="button"
                        data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </form>
    </div>
</div>