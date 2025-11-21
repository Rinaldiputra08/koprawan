<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">{{ $data->id ? 'Edit Penerimaan' : 'Tambahan Penerimaan' }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="{{ $data->id ? route('pembelian.penerimaan-produk.update', $data->id) : route('pembelian.penerimaan-produk.store') }}" method="POST" id="form-action">
            @csrf
            @if ($data->id) @method('put') @endif
            
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Tagihan</label>
                            <div class="demo-inline-spacing radio-tagihan">
                                <div class="custom-control custom-radio">
                                    <input type="radio" name="tagihan" id="tanpa" value="0" class="custom-control-input">
                                    <label for="tanpa" class="custom-control-label">Tanpa Tagihan</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" name="tagihan" id="dengan" value="1" class="custom-control-input">
                                    <label for="dengan" class="custom-control-label">Dengan Tagihan</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">&nbsp;</label>
                            <div class="demo-inline-spacing">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" {{ getConfig('purchase_order') ? 'checked' : '' }} id="pemesanan" name="pemesanan" class="custom-control-input">
                                    <label for="pemesanan" class="custom-control-label">Ambil dari pemesanan</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 {{ !getConfig('purchase_order') ? 'd-none' : '' }} pemesanan-group">
                        <div class="form-group">
                            <label for="">Nomor Pemesanan</label>
                            <div class="input-group">
                                <input type="text" name="nomor_pemesanan" readonly class="form-control">
                                <div class="input-group-append cursor-pointer">
                                    <div class="input-group-text">
                                        <strong>Cari</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 {{ !getConfig('purchase_order') ? 'd-none' : '' }} pemesanan-group">
                        <div class="form-group">
                            <label for="">Tanggal Pemesanan</label>
                            <input type="text" disabled name="tanggal_pemesanan" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Tanggal Penerimaan</label>
                            <input type="text" name="tanggal_penerimaan" value="{{ $data->tanggal_penerimaan }}" readonly class="form-control datepicker">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Supplier</label>
                            <select name="supplier" class="form-control select2" data-placeholder="Pilih Supplier">
                                <option></option>
                                @foreach ($supplier as $sp)
                                <option value="{{ $sp->id }}">{{ $sp->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Keterangan</label>
                            <textarea name="keterangan" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <fieldset>
                            <legend>Daftar Produk</legend>
                            <div class="row detail-produk">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Kode Produk</label>
                                        <input type="hidden" id="id_produk">
                                        <input type="text" id="kode_produk" class="form-control">
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
                                        <input type="text" id="harga_satuan"  class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Diskon</label>
                                        <div class="input-group">
                                            <input type="number" maxlength="3" value="0" min="0" max="100" id="diskon" class="form-control">
                                            <div class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="">&nbsp;</label>
                                        <input type="text" id="nominal_diskon" value="0" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Qty</label>
                                        <input type="number" min="1" value="1" id="qty"  class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Sub Total</label>
                                        <input type="text" id="sub_total" disabled class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Sisa Stok</label>
                                        <input type="text" id="stok" disabled class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">&nbsp;</label>
                                        <div class="d-flex justify-content-end">
                                            <button class="btn btn-primary mr-1 add-item" type="button">Tambah</button>
                                            <button class="btn btn-primary mr-1 edit-item hidden" type="button">Edit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-12 table-responsive">
                                    <table class="table table-sm table-bordered table-hover table-stripped" id="table-item">
                                        <thead>
                                            <tr>
                                                <th>Kode Produk</th>
                                                <th>Nama Produk</th>
                                                <th class="text-right">Harga</th>
                                                <th class="text-right">Diskon</th>
                                                <th class="text-center">Qty</th>
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
                                            <th>PPN</th>
                                            <th class="text-right" id="ppn">0</th>
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
                <a class="btn btn-secondary referensi-order" href="{{ url('pembelian/penerimaan-produk/cari') }}" data-method="GET">Referensi Penerimaan</a>
                <div class="float-right">
                    <button class="btn btn-primary btn-save" type="submit">Simpan</button>
                    <button class="btn btn-outline-primary waves-effect" type="button" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </form>
    </div>
</div>