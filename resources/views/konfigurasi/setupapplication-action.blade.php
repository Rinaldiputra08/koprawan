<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">{{ $data->id ? 'Edit' : 'Tambah' }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form id="form-action" action="{{ $data->id ? route('setup-aplikasi.update', $data->id) : route('setup-aplikasi.store') }}" method="{{ $data->id ? 'PUT' : 'POST' }}">
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="">Nama</label>
                            <input type="text" name="nama" class="form-control" {{ $data->id ? 'disabled' : '' }} value="{{ $data->name }}">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="">Nilai</label>
                            <textarea name="nilai" rows="10" class="form-control" @if($data->value_json) data-tipe="json" @else data-tipe="plaintext" @endif>{{ $data->value }}</textarea>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="">Jenis</label>
                            <div class="demo-inline-spacing radio-jenis">
                                <div class="custom-control custom-radio">
                                    <input type="radio" name="jenis" {{ !$data->value_json ? 'checked' : '' }} id="plaintext" value="plaintext" class="custom-control-input">
                                    <label for="plaintext" class="custom-control-label">Plaintext</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" name="jenis" {{ $data->value_json ? 'checked' : '' }} id="json" value="json" class="custom-control-input">
                                    <label for="json" class="custom-control-label">JSON</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="alert alert-danger">
                            <div class="alert-body">
                                <h6><strong>Tips</strong></h6>
                                <ul class="pl-1">
                                    <li>Isi nilai dengan <code>1</code> atau <code>0</code> untuk nilai boolean.</li>
                                    <li>Gunakan format JSON untuk nilai json.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary btn-save" type="submit">Simpan</button>
                <button class="btn btn-outline-primary waves-effect" data-dismiss="modal" type="button">Tutup</button>
            </div>
        </form>
    </div>
</div>