<link rel="stylesheet" type="text/css" href="{{ asset('') }}assets/vendors/css/forms/select/select2.min.css">
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel16">Login as</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form id="form" method="post" action="{{ route('login-as-post') }}">
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="login-as-username"></label>
                            <select class="form-control select2" name="username" id="login-as-username">
                                @foreach ($users as $user)
                                    <option value="{{ $user->username }}">{{ $user->username . ' - ' . $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-save">Proses</button>
                <button type="button" class="btn btn-outline-primary waves-effect" data-dismiss="modal">Tutup</button>
            </div>
        </form>
    </div>
</div>
<script src="{{ asset('') }}assets/vendors/js/forms/select/select2.full.min.js"></script>
<script>
    $('.select2').select2()  
</script>
