<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel16">{{ $getDetail->id ? 'Edit Menu' : 'Tambah Menu' }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form id="form-menu" method="post">
            <div class="modal-body">
                @csrf
                <input type="hidden" name="id" value="{{ $getDetail->id }}">
                <div class="row">
                    {{-- <div class="col-md-6">
                        <div class="form-group">
                            <label for="kode_menu">Kode Menu</label>
                            <input type="text" class="form-control" id="kode_menu" name="kode_menu"
                                placeholder="ex: mn001 or mn-001-s001" value="{{ $getDetail->kode_menu }}">
                        </div>
                    </div> --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_menu">Nama Menu</label>
                            <input type="text" class="form-control" id="nama_menu" name="nama_menu"
                                placeholder="Nama menu" value="{{ $getDetail->nama_menu }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="url">URL</label>
                            <input type="text" class="form-control" id="url" name="url" placeholder="URL"
                                value="{{ $getDetail->url }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="icon">Icon Menu</label>
                            <input type="text" class="form-control" id="icon" name="icon" placeholder="Icon Menu"
                                value="{{ $getDetail->icon }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="no_urut">No Urut</label>
                            <input type="text" class="form-control" id="no_urut" name="no_urut" 
                                value="{{ $getDetail->no_urut }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Level Menu</label>
                            <div class="demo-inline-spacing">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="level_menu" name="level" class="custom-control-input"
                                        value="main_menu" onclick="$('#option-menu').addClass('d-none');"
                                        {{ !$getDetail->main_menu ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="level_menu">Main Menu</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="sub_menu" name="level" class="custom-control-input"
                                        value="sub_menu" onclick="$('#option-menu').removeClass('d-none');"
                                        {{ $getDetail->main_menu ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="sub_menu">Sub Menu</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 {{ $getDetail->main_menu ? '' : ' d-none' }}" id="option-menu">
                        <div class="form-group">
                            <label>Main Menu</label>
                            <select class="form-control" name="main_menu" id="main_menu">
                                <option disabled selected>Pilih Main Menu</option>
                                @foreach ($level as $dm)
                                    <option value="{{ $dm->id }}"
                                        {{ $getDetail->main_menu == $dm->id ? ' selected' : '' }}>
                                        {{ $dm->nama_menu }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Jenis Bisnis</label>
                            <div class="demo-inline-spacing">
                                <div class="custom-control custom-radio">
                                    <input type="radio" {{ $getDetail->jenis_bisnis == 'SALES' ? 'checked' : '' }} name="jenis_bisnis" id="sales" class="custom-control-input" value="SALES">
                                    <label for="sales" class="custom-control-label">Sales</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" {{ $getDetail->jenis_bisnis == 'AFTER SALES' ? 'checked' : '' }} name="jenis_bisnis" id="after_sales" class="custom-control-input" value="AFTER SALES">
                                    <label for="after_sales" class="custom-control-label">After Sales</label>
                                </div>
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
