<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel16">Akses User</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form id="form-akses-edit" method="post">
            <div class="modal-body">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Level : {{ $jenis }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Menu</th>
                                        <th>Akses</th>
                                        <th>Tambah</th>
                                        <th>Edit</th>
                                        <th>Hapus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 0; @endphp
                                    @foreach ($data as $dt)
                                        @php $no++; @endphp
                                        <tr>
                                            <td>{{ $no }}</td>
                                            <td>{!! ($dt->level == 'sub_menu' ? '&nbsp; &nbsp; &nbsp; ' : '') . $dt->nama_menu !!}</td>
                                            <td>
                                                <div class="custom-control custom-switch custom-switch-info">
                                                    <input type="checkbox" class="custom-control-input update-akses"
                                                        data-kode_menu={{ $dt->kode_menu }} data-jenis="akses"
                                                        value="{{ $dt->akses }}"
                                                        {{ $dt->akses == '1' ? 'checked' : '' }}
                                                        id="akses{{ $no }}" />
                                                    <label class="custom-control-label"
                                                        for="akses{{ $no }}">
                                                        <span class="switch-icon-left"><i
                                                                data-feather="check"></i></span>
                                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="custom-control custom-switch custom-switch-info">
                                                    <input type="checkbox" class="custom-control-input update-akses"
                                                        data-kode_menu={{ $dt->kode_menu }} data-jenis="tambah"
                                                        value="{{ $dt->tambah }}"
                                                        {{ $dt->tambah == '1' ? 'checked' : '' }}
                                                        id="tambah{{ $no }}" />
                                                    <label class="custom-control-label"
                                                        for="tambah{{ $no }}">
                                                        <span class="switch-icon-left"><i
                                                                data-feather="check"></i></span>
                                                        <span class="switch-icon-right"><i
                                                                data-feather="x"></i></span>
                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="custom-control custom-switch custom-switch-info">
                                                    <input type="checkbox" class="custom-control-input update-akses"
                                                        data-kode_menu={{ $dt->kode_menu }} data-jenis="edit"
                                                        value="{{ $dt->edit }}"
                                                        {{ $dt->edit == '1' ? 'checked' : '' }}
                                                        id="edit{{ $no }}" />
                                                    <label class="custom-control-label"
                                                        for="edit{{ $no }}">
                                                        <span class="switch-icon-left"><i
                                                                data-feather="check"></i></span>
                                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="custom-control custom-switch custom-switch-info">
                                                    <input type="checkbox" class="custom-control-input update-akses"
                                                        data-kode_menu={{ $dt->kode_menu }} data-jenis="hapus"
                                                        value="{{ $dt->hapus }}"
                                                        {{ $dt->hapus == '1' ? 'checked' : '' }}
                                                        id="hapus{{ $no }}" />
                                                    <label class="custom-control-label"
                                                        for="hapus{{ $no }}">
                                                        <span class="switch-icon-left"><i
                                                                data-feather="check"></i></span>
                                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary waves-effect" data-dismiss="modal">Tutup</button>
            </div>
        </form>
    </div>
</div>
