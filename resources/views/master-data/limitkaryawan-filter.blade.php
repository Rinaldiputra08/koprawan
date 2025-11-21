<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel16"></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form id="form-filter" method="get">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="nama">Pilih Bulan</label>
                            <select class="form-control select2" name="bulan" id="bulan">
                                <option  disabled selected>List Bulan</option>
                                @foreach ($tgl as $data)
                                    <option value="{{$data->periode}}">
                                        {{dateFormat($data->periode.'01', 'm-Y')}}
                                    </option>                                    
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-search">Cari Data</button>
                <button type="button" class="btn btn-outline-primary waves-effect" data-dismiss="modal">Tutup</button>
            </div>
        </form>
    </div>
</div>
