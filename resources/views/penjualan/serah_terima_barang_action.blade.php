<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">{{ $data->id ? 'Edit serah terima barang' : 'Tambahan serah terima barang' }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="{{ $data->id ? route('penjualan.serah-terima-barang.update', $data->id) : route('penjualan.serah-terima-barang.store') }}" method="POST" id="form-action">
            @csrf
            @if ($data->id) @method('put') @endif
            
            <div class="modal-body">

                <div class="row">
                    <div class="col-12">
                        <fieldset>
                            <div class="row detail-penjualan">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Kode Penjualan Langsung</label>
                                        <input type="text" id="nomor_penjualan" name="nomor_penjualan" class="form-control">
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Nama Karyawan</label>
                                        <input type="text" id="nama_karyawan" name="nama_karyawan" disabled class="form-control">
                                    </div>
                                </div>

                                {{-- <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Tanggal Serah Terima</label>
                                        <input type="text" id="tanggal_penerimaan" name="tanggal_penerimaan" readonly class="form-control datepicker">
                                    </div>
                                </div> --}}

                            </div>

                            <div class="row mt-1">
                                <div class="col-12 table-responsive">
                                    <table class="table table-sm table-bordered table-hover table-stripped" id="table-item">
                                        <thead>
                                            <tr>
                                                <th>Nama Produk</th>
                                                <th class="text-center">Qty</th>
                                                <th class="text-center">Harga Satuan</th>
                                                <th class="text-center">Diskon</th>
                                                <th class="text-center">Sub Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                        </tbody>
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
                                            <th class="text-right" id="diskon">0</th>
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
                    <button class="btn btn-outline-primary waves-effect" type="button" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </form>
    </div>
</div>