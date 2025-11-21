<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel16">Cari Produk</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <table class="table table-hover table-striped" id="list-produk">
                <thead>
                    <tr>
                        <th colspan="3">
                            <input type="text" name="filter_produk" placeholder="Cari produk disini.." class="form-control">
                        </th>
                    </tr>
                    <tr>
                        <th>Foto</th>
                        <th>Kode</th>
                        <th>Nama</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($list_produk as $produk)
                        <tr class="cursor-pointer" data-dismiss="modal">
                            <td class="d-none">{{ $produk->id }}</td>
                            <td>{!! $produk->fotoThumbnail ? '<img class="rounded" style="width: 60px;height:60px" src="'.asset('storage/images/produk/small_'.$produk->fotoThumbnail->nama_file).'" />' : $produk->fotoThumbnail !!}</td>
                            <td>{{ $produk->kode }}</td>
                            <td>{{ $produk->nama }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
