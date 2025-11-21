<div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">List User</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-12">
                    <input type="text" name="search" placeholder="Cari user" class="form-control">
                </div>
                <div class="col-12 table-responsive mt-1">
                    <table class="table table-bordered table-stripped" id="list-user">
                        <thead>
                            <tr>
                                <th>Nik</th>
                                <th>Nama </th>
                                <th>Total Transaksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                            <tr data-dismiss="modal" data-id="{{ $item->id }}" class="cursor-pointer">
                                <td class="hidden" data-target="id">{{ $item->id }}</td>
                                <td data-target="nik">{{ $item->nik }}</td>
                                <td data-target="nama">{{ $item->nama }}</td>
                                <td data-target="total_transaksi">{{ $item->piutang->nominal_formatted ?? 0 }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>