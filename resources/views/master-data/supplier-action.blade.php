<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel16">{{ $data->id ? 'Edit Supplier' : 'Tambah Supplier' }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form id="form-action" method="POST"
            action="{{ $data->id ? route('master-data.supplier.update', $data->id) : route('master-data.supplier.store') }}">
            @csrf
            @if ($data->id)
                @method('put')
            @endif
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Nama</label>
                            <input type="text" class="form-control" value="{{ $data->nama }}" name="nama">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Nomor Telepon</label>
                            <input type="text" class="form-control" value="{{ $data->nomor_telepon }}" name="nomor_telepon">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label for="">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="5">{{ $data->alamat }}</textarea>
                    </div>
                </div>
                @if ($data->id)
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Aktif</label>
                                <div class="demo-inline-spacing">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" {{ $data->aktif == 1 ? 'checked' : '' }} name="aktif"
                                            id="aktif" value="1" class="custom-control-input">
                                        <label for="aktif" class="custom-control-label">Aktif</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" {{ $data->aktif == 0 ? 'checked' : '' }} name="aktif"
                                            id="non-aktif" value="0" class="custom-control-input">
                                        <label for="non-aktif" class="custom-control-label">Non Aktif</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-save">Simpan</button>
                <button type="button" class="btn btn-outline-primary waves-effect" data-dismiss="modal">Tutup</button>
            </div>
        </form>
    </div>
</div>
