<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel16">Akses User</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form id="form-akses" action="{{ $role->id ? route('akses-role.update',$role->id) : route('akses-role.store') }}" method="post">
            @csrf @method('put')
            <div class="modal-body">
                <h4>Level : {{ $role->name }}</h4>   
                <div class="form-group">
                    <input type="text" class="form-control" id="search_value" name="search_value"
                        placeholder="Search...">
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nama Menu</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="permissions_data">
                            @foreach ($menus as $menu)
                                <tr>
                                    <td>{!! $menu->main_menu ? '&nbsp;&nbsp;&nbsp;&mdash;&nbsp;' : '' !!}{{ $menu->nama_menu }}</td>
                                    <td>
                                        @foreach ($menu->permissions as $permission)
                                            <div style="margin:1px 0;" class="custom-control custom-switch custom-control-inline">
                                                <input type="checkbox" class="custom-control-input"
                                                {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }} 
                                                name="permissions[]" value="{{ $permission->name }}" id="customSwitch1{{ $permission->id }}">
                                                <label class="custom-control-label" for="customSwitch1{{ $permission->id }}">{{ explode(' ',$permission->name)[0] }}</label>
                                            </div>
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-gradient-primary btn-save">Simpan</button>
                <button type="button" class="btn btn-outline-primary waves-effect" data-dismiss="modal">Tutup</button>
            </div>
        </form>
    </div>
</div>
