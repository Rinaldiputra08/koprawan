<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel16">{{ $data->id ? 'Edit User' : 'Tambah User' }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form id="form-action" method="POST"
            action="{{ $data->id ? route('master-data.produk.update', $data->id) : route('master-data.produk.store') }}">
            @csrf
            @if ($data->id)
                @method('put')
            @endif
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kode">kode</label>
                            <input type="text" class="form-control" id="kode" name="kode" placeholder="kode"
                                value="{{ $data->kode }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" placeholder="nama"
                                value="{{ $data->nama }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Judul</label>
                            <textarea name="judul" rows="5" class="form-control" placeholder="judul Produk">{{ $data->judul }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Deskripsi</label>
                            <textarea name="deskripsi" rows="5" class="form-control" placeholder="Deskripsi Produk">{{ $data->deskripsi }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="harga_beli">Harga Beli</label>
                            <input type="text" class="form-control" onkeyup="this.value=formatAngka(this.value)"
                                id="harga_beli" name="harga_beli" value="{{ $data->harga_beli_formatted }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="harga_jual">Harga Jual</label>
                            <input type="text" class="form-control" onkeyup="this.value=formatAngka(this.value)"
                                id="harga_jual" name="harga_jual" value="{{ $data->harga_jual_formatted }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kategori</label>
                            <select class="form-control select2" name="kategori" id="kategori">
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategori as $kategori)
                                    <option value="{{ $kategori->id }}"
                                        {{ $data->kategori_id == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Merek</label>
                            <select class="form-control select2" name="merek" id="merek">
                                <option value="">Pilih Merek</option>
                                @foreach ($merek as $merek)
                                    <option value="{{ $merek->id }}"
                                        {{ $data->merek_id == $merek->id ? 'selected' : '' }}>{{ $merek->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12 avatar-group">
                        <img src="{{ $data->fotoThumbnail ? asset('storage/images/produk/medium_'.$data->fotoThumbnail->nama_file) : asset('assets/images/avatars/delivery.jpg') }}" alt="delivery avatar" class="delivery-avatar users-avatar-shadow rounded mr-2 my-25 cursor-pointer" height="200" width="200">
                    </div>
                    <div class="col-md-12 align-content-start">
                        <div class="croppie-group d-none">
                            <div class="picture-canvas"></div>
                        </div>
                    </div>
                    <div class="form-group col-md-12 browse-picture mt-1">
                        <label class="btn btn-primary mr-75 mb-0" for="change-picture">
                            <span class="label-button">Pilih Foto</span>
                            <input class="form-control" type="file" id="change-picture" name="foto" hidden accept="image/jpeg, image/jpg" />
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
                    </div>
                    <div class="form-group col-md-12 thumbnail-result">
                        <div class="row col-md-12">
                            @foreach ($data->foto as $key => $foto)
                                <div class="thumbnail-container">
                                    <img src="{{ asset('storage/images/produk/small_').$foto->nama_file }}" alt="" class="rounded mb-1">
                                    <a class="btn btn-xs btn-danger btn-remove" data-id="{{ $foto->id }}" onclick="produkJs.removePhotoEdit(this)"><i data-feather='trash-2'></i></a>
                                    <div class="custom-control custom-checkbox foto-thumbnail">
                                        <input type="checkbox" name="foto_thumbnail[]" {{ $foto->thumbnail == '1' ? 'checked' : '' }} id="{{ $foto->id.'thumbnail' }}" value="{{ $key }}" class="custom-control-input">
                                        <label for="{{ $foto->id.'thumbnail' }}" class="custom-control-label"></label>
                                    </div>
                                    <input type="hidden" name="remove_upload_foto[]" />
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if ($data->id)
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Aktif</label>
                                <div class="demo-inline-spacing">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" {{ $data->aktif == 1 ? 'checked' : '' }} name="aktif" id="aktif" value="1"
                                            class="custom-control-input">
                                        <label for="aktif" class="custom-control-label">Aktif</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" {{ $data->aktif == 0 ? 'checked' : '' }}  name="aktif" id="non-aktif" value="0"
                                            class="custom-control-input">
                                        <label for="non-aktif" class="custom-control-label">Non Aktif</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="alert alert-success">
                                <div class="alert-body">Stock Fisik: {{ $data->stock_fisik }} &mdash; Stock Free: {{ $data->stock_free }}</div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-save">Simpan</button>
                    <button type="button" class="btn btn-outline-primary waves-effect"
                        data-dismiss="modal">Tutup</button>
                </div>
        </form>
    </div>
</div>
