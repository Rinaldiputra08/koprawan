<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">{{ $data->id ? 'Edit berita acara' : 'Tambahan berita acara' }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="{{ $data->id ? route('gudang.berita-acara-gudang.update', $data->id) : route('gudang.berita-acara-gudang.store') }}" method="POST" id="form-action">
            @csrf
            @if ($data->id) @method('put') @endif
            
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Tanggal Berita Acara</label>
                            <input type="text" name="tanggal_berita_acara" value="{{ $data->tanggal_berita_acara }}" readonly class="form-control datepicker">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kode">Jenis Berita Acara</label>
                            <select class="select2 form-control form-control-lg" name="jenis">
                                <option value="">Pilih jenis</option>
                                <option {{$data->jenis ? 'selected' : ''}}>Masuk</option>
                                <option {{$data->jenis ? 'selected' : ''}}>Keluar</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="">Keterangan</label>
                            <textarea name="keterangan" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <fieldset>
                            <div class="row detail-produk">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Kode Produk</label>
                                        <input type="hidden" id="id_produk">
                                        <input type="text" id="kode_produk" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Nama Produk</label>
                                        <input type="text" id="nama_produk" disabled class="form-control">
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Qty</label>
                                        <input type="number" min="1" value="1" id="qty"  class="form-control">
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="">Keterangan</label>
                                        <textarea name="keterangan_produk" id="keterangan" rows="3" class="form-control"></textarea>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for=""></label>
                                        <div class="d-flex justify-content-right">
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
                                                <th class="text-center">Qty</th>
                                                <th>Keterangan</th>
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