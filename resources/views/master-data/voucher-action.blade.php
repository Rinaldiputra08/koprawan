<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel16">{{ $data->id ? 'Edit Voucher' : 'Tambah Voucher' }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form id="form-action" method="POST"
            action="{{ $data->id ? route('master-data.voucher.update', $data->id) : route('master-data.voucher.store') }}">
            @csrf
            @if ($data->id)
                @method('put')
            @endif
            <div class="modal-body">
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kode_voucher">Kode Voucher</label>
                            <input type="text" class="form-control" id="kode_voucher" name="kode_voucher" placeholder="Jika kosong maka auto generate"
                                value="{{ $data->kode_voucher }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" placeholder="nama"
                                value="{{ $data->nama }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Tanggal Awal</label>
                            <div class="input-group">
                                <input class="form-control datepicker" readonly type="text" placeholder ="Pilih Tanggal Awal" name="tanggal_awal" id="tanggal_awal" value="{{ $data->tanggal_awal_formatted }}" >
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
                                <input class="form-control datepicker" type="text" placeholder ="Pilih Tanggal Akhir" name="tanggal_akhir" id="tanggal_akhir" value = "{{ $data->tanggal_akhir_formatted }}" readonly>
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
                            <label for="">Nominal</label>
                            <input type="text" value="{{ $data->nominal_formatted }}" onkeyup="this.value=formatAngka(this.value)" name="nominal" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kode">Jenis Voucher</label>
                            <select class="select2 form-control form-control-lg" name="jenis">
                                <option value="">Pilih jenis</option>
                                <option {{$data->jenis == "Voucher user" ? 'selected' : ''}}>Voucher  user</option>
                                <option {{$data->jenis == "Voucher umum" ? 'selected' : ''}}>Voucher  umum</option>
                            </select>
                        </div>
                    </div>

                    @if ($data->id)
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Kriteria</label>
                                <div class="demo-inline-spacing">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" {{ $data->ketentuan ? 'checked' : '' }} name="has_kriteria"
                                            id="aktif" value="1" class="custom-control-input">
                                        <label for="aktif" class="custom-control-label">Ya</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" {{ !$data->ketentuan ? 'checked' : '' }} name="has_kriteria"
                                            id="non-aktif" value="0" class="custom-control-input">
                                        <label for="non-aktif" class="custom-control-label">Tidak</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else

                    <div class="col-md-6">
                        <label for="kriteria">Kriteria</label>
                        <div class="demo-inline-spacing">
                            <div class="custom-control custom-radio">
                                <input type="radio" id="customRadio1" value="1" name="has_kriteria" class="custom-control-input"/>
                                <label class="custom-control-label" for="customRadio1">Ya</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="customRadio2" value="0" name="has_kriteria" class="custom-control-input" />
                                <label class="custom-control-label" for="customRadio2">Tidak</label>
                            </div>
                        </div>
                    </div>

                    @endif

                    <div class="col-md-12 {{$data->ketentuan ? '' : 'd-none'}}">
                        <div class="form-group" id="kriteria">
                            <label>Voucher Kriteria</label>
                            <select class="form-control select2" multiple name="kriteria[]" id="kriteria">
                                <option disabled >Pilih Kriteria</option>
                                @foreach ($kriteria as $kriteria)
                                    <option value="{{ $kriteria->id }}" {{$data->id ? (in_array($kriteria->id, $list_kriteria) ? 'selected' : '') : ''}}>{{ $kriteria->nama }} ({{ $kriteria->nominal_formatted }})</option>
                                @endforeach
                            </select>
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

{{-- <script type="text/javascript">
    function show(str){
        document.getElementById('kriteria').style.display = 'block';
    }
    function show2(sign){
        document.getElementById('kriteria').style.display = 'none';   
    }
</script> --}}





