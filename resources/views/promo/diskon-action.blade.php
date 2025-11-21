<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel16">{{ $data->id ? 'Edit Diskon' : 'Tambah Diskon' }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form id="form-action" method="POST"
            action="{{ $data->id ? route('promo.diskon.update', $data->id) : route('promo.diskon.store') }}">
            @csrf
            @if ($data->id)
                @method('put')
            @endif
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Tanggal Awal</label>
                            <div class="input-group">
                                <input class="form-control datepicker" readonly type="text" placeholder ="Pilih Tanggal Awal" name="tanggal_awal" id="tanggal_awal" value="{{ $data->tanggal_awal_berlaku_formatted }}" >
                                <div class="input-group-addon input-group-append" id="button-addon1">
                                    <button class="btn btn-outline-primary waves-effect find-spk" type="button"><i data-feather="calendar"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Jam Awal</label>
                            <div class="input-group">
                                <select name="jam_awal"  class="form-control">
                                    <option value="">Pilih Jam</option>
                                    @for ($i = 0; $i < 24; $i++)
                                        <option value="{{ sprintf('%02s',$i) }}" {{ $data->jam_awal == sprintf('%02s',$i) ? 'selected' : ''  }}>{{ sprintf('%02s',$i) }}</option>
                                    @endfor
                                </select>
                                <select name="menit_awal"  class="form-control">
                                    <option value="">Pilih Menit</option>
                                    @for ($i = 0; $i <= 59; $i++)
                                    <option value="{{ sprintf('%02s',$i) }}" {{ $data->menit_awal == sprintf('%02s',$i) ? 'selected' : ''  }}>{{ sprintf('%02s',$i) }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Tanggal Akhir</label>
                            <div class="input-group">
                                <input class="form-control datepicker" type="text" placeholder ="Pilih Tanggal Akhir" name="tanggal_akhir" id="tanggal_akhir" value = "{{ $data->tanggal_akhir_berlaku_formatted }}" readonly>
                                <div class="input-group-append" id="button-addon2">
                                    <button class="btn btn-outline-primary waves-effect find-spk" type="button"><i data-feather="calendar"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Jam Akhir</label>
                            <div class="input-group">
                                <select name="jam_akhir"  class="form-control">
                                    <option value="">Pilih Jam</option>
                                    @for ($i = 0; $i < 24; $i++)
                                    <option value="{{ sprintf('%02s',$i) }}" {{ $data->jam_akhir == sprintf('%02s',$i) ? 'selected' : ''  }}>{{ sprintf('%02s',$i) }}</option>
                                    @endfor
                                </select>
                                <select name="menit_akhir"  class="form-control">
                                    <option value="">Pilih Menit</option>
                                    @for ($i = 0; $i <= 59; $i++)
                                    <option value="{{ sprintf('%02s',$i) }}" {{ $data->menit_akhir == sprintf('%02s',$i) ? 'selected' : ''  }}>{{ sprintf('%02s',$i) }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Produk (kode-nama)</label>
                            <input type="hidden" value="{{ $data->produk_id }}" name="produk_id">
                            <div class="input-group">
                                <input type="text" value="{{ $data->produk ? $data->produk->kode : '' }}" data-url={{ route('promo.diskon.list-produk') }} class="form-control cari-produk" readonly placeholder="Kode" name="kode_produk">
                                <input type="text" data-url={{ route('promo.diskon.list-produk') }} name="nama_produk" class="form-control cari-produk" value="{{ $data->produk ? $data->produk->nama : '' }}" placeholder="Nama Produk" readonly>
                                <div class="input-group-append" >
                                    <button data-url={{ route('promo.diskon.list-produk') }} class="btn btn-outline-primary waves-effect cari-produk" type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Nominal</label>
                            <input type="text" value="{{ $data->nominal_formatted }}" onkeyup="this.value=formatAngka(this.value)" name="nominal" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-save">Simpan</button>
                <button type="button" class="btn btn-outline-primary waves-effect" data-dismiss="modal">Tutup</button>
            </div>
        </form>
    </div>
</div>
