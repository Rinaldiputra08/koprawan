<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel16">{{ $data->id ? 'Edit Voucher Kriteria' : 'Tambah Voucher Kriteria' }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form id="form-action" method="POST"
            action="{{ $data->id ? route('master-data.voucher-kriteria.update', $data->id) : route('master-data.voucher-kriteria.store') }}">
            @csrf
            @if ($data->id)
                @method('put')
            @endif
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kode">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" placeholder="nama"
                                value="{{ $data->nama }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nominal">Nominal</label>
                            <input type="text" class="form-control" onkeyup="this.value=formatAngka(this.value)"
                                id="nominal" name="nominal" value="{{ $data->nominal_formatted }}">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-save">Simpan</button>
                    <button type="button" class="btn btn-outline-primary waves-effect"
                        data-dismiss="modal">Tutup</button>
                </div>
        </form>
    </div>
</div>
