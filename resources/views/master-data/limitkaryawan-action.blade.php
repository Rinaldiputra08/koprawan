
<form id="form" action="{{ $url ?? route('master-data.limit-karyawan.store')}}" method="{{ isset($url) ? 'put' : 'post'}}" >

    @csrf
    <div class="row">
        <div class="col-md-2" >
            <div class="form-group">
                <label for="bulan" >Periode</label>
                <input type="text" class="form-control datepicker periode" name="periode"  value="{{ isset($periode) ? dateFormat($periode.'01', 'm-Y') : '' }}" readonly="readon]y" />
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label>Divisi</label>
                    <select class="form-control select2" name="divisi" id="divisi">
                        <option value="">Pilih Divisi</option>
                        <option value="IT">IT</option>
                        <option value="SERVICE">Service</option>
                    </select>
            </div>
        </div>
    </div>
    <div class="row" id="list-karyawan">
        
    </div>
    <div class="row">
        <div class="col-12">
            <button class="btn btn-primary btn-save d-none" type="submit">Simpan</button>
        </div>
    </div>
</form>
