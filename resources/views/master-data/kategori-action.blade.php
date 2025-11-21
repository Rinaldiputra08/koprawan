<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel16">{{ $data->id ? 'Edit Kategori' : 'Tambah Kategori' }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form id="form-action" method="POST"
            action="{{ $data->id ? route('master-data.kategori.update', $data->id) : route('master-data.kategori.store') }}">
            @csrf
            @if ($data->id)
                @method('put')
            @endif
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" placeholder="nama"
                                value="{{ $data->nama }}">
                        </div>
                    </div>
                    <div class="col-md-12 avatar-group">
                        <img src="{{ $data->foto ? asset('storage/images/kategori-produk/medium_' . $data->foto->nama_file) : asset('assets/images/avatars/delivery.jpg') }}"
                            alt="delivery avatar"
                            class="category-img users-avatar-shadow rounded mr-2 my-25 cursor-pointer" height="200"
                            width="200" style="object-fit: cover">
                    </div>
                    <div class="col-md-12 align-content-start">
                        <div class="croppie-group d-none">
                            <div class="picture-canvas"></div>
                        </div>
                    </div>
                    <div class="form-group col-md-12 browse-picture mt-1">
                        <label class="btn btn-primary mr-75 mb-0" for="change-picture">
                            <span class="label-button">Pilih Foto</span>
                            <input class="form-control" type="file" id="change-picture" name="foto" hidden
                                accept="image/jpeg, image/jpg, image/png" />
                        </label>
                    </div>
                    <div class="form-group col-md-12 d-none rotate-button">
                        <button type="button" class="btn btn-primary rotate" data-deg="90">
                            <i data-feather='rotate-ccw'></i>
                        </button>
                        <button type="button" class="btn btn-primary rotate" data-deg="-90">
                            <i data-feather='rotate-cw'></i>
                        </button>
                        <button class="btn btn-success crop" type="button">Crop Foto</button>
                        <input type="hidden" name="upload_foto" readonly>
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
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-save">Simpan</button>
                <button type="button" class="btn btn-outline-primary waves-effect" data-dismiss="modal">Tutup</button>
            </div>
        </form>
    </div>
</div>
