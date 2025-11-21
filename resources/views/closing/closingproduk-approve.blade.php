<div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Approve Closing</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="{{url('closing/post-approval/'.$periode)}}" id="form-action">
            @csrf
            <div class="modal-body">
                <div class="row">
                   <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Approvel</label>
                            <div class="demo-inline-spacing radio-approval">
                                <div class="custom-control custom-radio">
                                    <input type="radio" name="approve" id="approve" value="1" class="custom-control-input">
                                    <label for="approve" class="custom-control-label">Approve</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" name="approve" id="tolak" value="0" class="custom-control-input">
                                    <label for="tolak" class="custom-control-label">Tolak</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="keterangan"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <div class="float-right">
                    <button class="btn btn-primary btn-save" type="submit">Proses</button>
                    <button class="btn btn-outline-primary waves-effect" type="button" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </form>
    </div>
</div>