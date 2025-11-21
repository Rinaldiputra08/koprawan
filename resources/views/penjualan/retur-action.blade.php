<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">{{ $data->id ? 'Edit Penjualan Langsung' : 'Tambah Penjualan Langsung' }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="{{ $data->id ? route('penjualan.retur-penjualan.update', $data->id) : route('penjualan.retur-penjualan.store') }}" method="POST" id="form-action">
            @csrf
            @if ($data->id) @method('put') @endif
            
            <div class="modal-body">
                
                
                
                
                <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Tanggal Return</label>
                                <input type="text" name="tanggal_return" value="{{ $data->tanggal_return}}" readonly class="form-control datepicker">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="">Keterangan</label>
                                <textarea name="keterangan" rows="3" class="form-control"></textarea>
                            </div>
                        </div>
                        <fieldset>
                            <legend>Data Transaksi</legend>
                            <div class="row detail-produk">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">No Transaksi</label>
                                        <input type="text" id="no_transaksi" class="form-control" name="nomor" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Nama Karyawan</label>
                                        <input type="text" name="nama" id="nama" disabled class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Tanggal Pembelian</label>
                                        <input type="text" name="tanggal" id="tanggal" disabled class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Total</label>
                                        <input type="number" name="total" id="total"  class="form-control" readonly>
                                    </div>
                                </div>
                            </div>               
                        </fieldset>      
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