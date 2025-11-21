<div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">List Penerimaan</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-12">
                    <input type="text" name="search" data-url="{{ url('pembelian/penerimaan-produk/cari') }}" placeholder="Cari produk" class="form-control">
                </div>
                <div class="col-12 table-responsive mt-1">
                    <table class="table table-bordered table-stripped" id="list-penerimaan">
                        <thead>
                            <tr>
                                <th>Nomor</th>
                                <th>Supplier</th>
                                <th>Total</th>
                                <th>PPN</th>
                                <th>Grand Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $item)
                            <tr data-id="{{ $item->id }}" data-dismiss="modal" class="cursor-pointer" data-url="{{ url('pembelian/penerimaan-produk/cari') }}">
                                <td data-target="nomor_penerimaan">{{ $item->nomor }}</td>
                                <td class="hidden" data-target="tanggal_penerimaan">{{ $item->tanggal_penerimaan_formatted }}</td>
                                <td class="hidden" data-target="supplier">{{ $item->supplier->id }}</td>
                                <td>{{ $item->supplier->nama }}</td>
                                <td>{{ $item->total_formatted }}</td>
                                <td>{{ $item->ppn_formatted }}</td>
                                <td>{{ numberFormat($item->total + $item->ppn) }}</td>
                            </tr>
                            @empty
                            <tr class="text-center">
                                <td colspan="5">
                                    <small>Tidak ada data penerimaan</small>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>