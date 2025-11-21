<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Detail</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <th>Nama produk</th>
                                <td>{{ $data->nama }}</td>
                            </tr>
                            <tr>
                                <th>Judul</th>
                                <td>{{ $data->judul }}</td>
                            </tr>
                            <tr>
                                <th>Deskripsi</th>
                                <td>{{ $data->deskripsi }}</td>
                            </tr>
                            <tr>
                                <th>Harga Jual</th>
                                <td>{{ $data->harga_jual_formatted }}</td>
                            </tr>
                            <tr>
                                <th>Stock Free</th>
                                <td>{{ $data->stock_free }}</td>
                            </tr>
                            <tr>
                                <th>Stock Fisik</th>
                                <td>{{ $data->stock_fisik}}</td>
                            </tr>
                            <tr>
                                <th>Deskripsi </th>
                                <td>{{ $data->deskripsi}}</td>
                            </tr>
                        </tbody>
                    </table>
                    <section id="component-swiper-centered-slides">
                        <div class="card bg-transparent shadow-none" style="margin-right: 15px">
                            <div class="card-header">
                                <h4 class="card-title">Foto Produk Titipan</h4>
                            </div>
                            <div class="card-body">
                                <div class="swiper-centered-slides swiper-container ">
                                    <div class="swiper-wrapper">
                                        @foreach ($data->foto as $key => $foto)
                                            <div class="swiper-slide rounded swiper-shadow" id="gallery">
                                                <a href="{{asset('storage/images/produk-titipan/medium_').$foto->nama_file }}" data-toggle="lightbox" data-max-width="600"> 
                                                    <img src="{{ asset('storage/images/produk-titipan/small_').$foto->nama_file }}"  class="rounded mb-1 img-fluid">
                                                </a>
                                            </div>
                                        @endforeach    
                                    </div>
                                    <!-- Add Arrows -->
                                    <div class="swiper-button-next"></div>
                                    <div class="swiper-button-prev"></div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <div>
                        <span class="font-italic float-right">Diajukan Oleh: {{ $data->karyawan->nama }}, {{ $data->tanggal_formatted }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline-primary waves-effect" data-dismiss="modal">Tutup</button>
        </div>
    </div>
</div>