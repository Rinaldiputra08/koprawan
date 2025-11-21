<div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">List Penjualan</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-12">
                    <input type="text" name="search" data-url="{{ url('penjualan/serah-terima-barang/cari-penjualan') }}" placeholder="Cari penjualan" class="form-control">
                </div>
                <div class="col-12 table-responsive mt-1">
                    <table class="table table-bordered table-stripped" id="list-penjualan">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Karyawan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $item)
                            <tr data-dismiss="modal" data-id="{{ $item->id }}" class="cursor-pointer" data-url="{{ url('penjualan/serah-terima-barang/cari-penjualan') }}">
                                <td data-target="nomor_penjualan">{{ $item->nomor }}</td>
                                <td data-target="nama_karyawan">{{ $item->karyawan->nama }}</td>
                            </tr>
                            @empty
                            <tr class="text-center">
                                <td colspan="5">
                                    <small>Tidak ada data list penjualan</small>
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