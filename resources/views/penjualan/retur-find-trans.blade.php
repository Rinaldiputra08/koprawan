<div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">List Transaksi</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-12">
                    <input type="text" name="search" data-url="{{ url('penjualan/cari-transaksi') }}" placeholder="Cari Transaksi" class="form-control">
                </div>
                <div class="col-12 table-responsive mt-1">
                    <table class="table table-bordered table-stripped" id="list-trans">
                        <thead>
                            <tr>
                                <th>Nomor</th>
                                <th>Tanggal</th>
                                <th>Karyawan</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $item)                 
                                <tr data-dismiss="modal" class="cursor-pointer">
                                    <td class="hidden" data-target="penjualan_id">{{ $item->id }}</td>
                                    <td data-target="no_transaksi">{{ $item->nomor }}</td>
                                    <td data-target="tanggal">{{ $item->tanggal }}</td>
                                    <td data-target="nama">{{ $item->karyawan->nama }}</td>
                                    <td data-target="total">{{ $item->grand_total ?? 0}}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-danger text-center" >Data Tidak Ditemukan</td>
                                </tr>
                            @endforelse 
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>