<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel16">
                {{ $pemetaanMekanik->id ? 'Edit Pemetaan Mekanik' : 'Tambah Pemetaan mekanik' }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form id="form-mekanik" method="post">
            <div class="modal-body">
                @csrf
                <input type="hidden" name="id" value="{{ $pemetaanMekanik->id }}">
                <input type="hidden" name="mekanik_id" value="{{ $pemetaanMekanik->mekanik_id }}" id="idMekanik">
                <input type="hidden" name="stall_id" value="{{ $pemetaanMekanik->stall_id }}" id="idStall">
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label for="nama">Mekanik</label>
                        <div class="input-group mb-2">
                            <input type="text" class="form-control find-mekanik" id="mekanik" placeholder="Pilih Mekanik"
                                value="{{ $pemetaanMekanik->mekanik->nama?? '' }}" name="mekanik" readonly>
                            <div class="input-group-append">
                                <span class="input-group-text cursor-pointer"><i data-feather="search"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 form-group">
                        <label for="stall">Stall</label>
                        <div class="input-group mb-2">
                            <input type="text" class="form-control find-stall" id="stall" placeholder="Pilih Stall"
                                value="{{ $pemetaanMekanik->stall->nama_stall?? '' }}" name="stall" readonly>
                            <div class="input-group-append">
                                <span class="input-group-text cursor-pointer"><i data-feather="search"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 form-group">
                        <label for="fp-range">Tanggal awal s/d tanggal akhir</label>
                        <div class="input-group mb-2">
                            <input type="text" class="form-control flatpickr-range flatpickr-input active"
                                name="tanggal" value="{{ $pemetaanMekanik->tanggal_awal ? $pemetaanMekanik->tanggal_awal. ' to ' : '' }}  {{ $pemetaanMekanik->tanggal_akhir }}" placeholder="YYYY-MM-DD to YYYY-MM-DD" readonly="readonly">
                            <div class="input-group-append">
                                <span class="input-group-text cursor-pointer"><i data-feather="octagon"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-save">Simpan</button>
                <button type="button" class="btn btn-outline-primary waves-effect" data-dismiss="modal">Tutup</button>
            </div>
        </form>
    </div>
</div>
