<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel16">{{ $data->id ? 'Pilih User' : '' }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form id="form-action" method="POST"
            action="{{ route('master-data.voucher.store-pemakai', $data->id)}}">
            @csrf
            <div class="modal-body">
                <fieldset>
                    <legend>Detail Voucher</legend>
                    <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kode_voucher">Kode Voucher</label>
                            <input type="text" class="form-control" id="kode_voucher" name="kode_voucher" placeholder="Jika kosong maka auto generate"
                                value="{{ $data->kode_voucher }}" readonly>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" placeholder="nama"
                                value="{{ $data->nama }}" readonly>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama">Nominal</label>
                            <input type="text" class="form-control" id="nama" name="nama" placeholder="nama"
                                value="{{ $data->nominal_formatted }}" readonly>
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Penerima Voucher</legend>
                    <div class="col-md-12">
                        <div class="form-group">
                            <div style="text-align: left">
                                <button class="btn btn-primary" type="button" id="pilih_user">Tambah</button>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-12 table-responsive">
                            <table class="table table-sm table-bordered table-hover table-stripped" id="table-user">
                                <thead>
                                    <tr style="text-align: center">
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <th>Total Transaksi</th>
                                        <th>Hapus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data->penerimaVoucher as $penerima)
                                        <tr class="select-user" data-id="{{$penerima->id}}">
                                            <input type="hidden" name="karyawan[]" value="{{$penerima->id}}">
                                            <td>{{$penerima->nik}}</td>
                                            <td>{{$penerima->nama}}</td>
                                            <td>{{$penerima->piutang->nominal_formatted ?? 0}}</td>
                                            <td class="text-center"><button class="btn btn-sm btn-danger remove-user" data-name="{{$penerima->nama}}" type="button">X</button></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </fieldset>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-save">Simpan</button>
                <button type="button" class="btn btn-outline-primary waves-effect" data-dismiss="modal">Tutup</button>
            </div>
        </form>
    </div>
</div>






