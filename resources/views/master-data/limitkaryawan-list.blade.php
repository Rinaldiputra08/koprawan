@foreach ($data as $item)
    <div class="col-md-4 mb-1">
        <div class="form-group">
            <label for="basicInput">Nama</label>
            <input type="text" class="form-control" disabled  value="{{$item->nama}}"/>
        </div>
    </div>
    <div class="col-md-4 mb-1">
        <div class="form-group">
            <label for="helpInputTop">Divisi</label>
            <input type="text" class="form-control" disabled value="{{$item->divisi}}" />
        </div>
    </div>
    <div class="col-md-4 mb-1">
        <div class="form-group">
            <label for="disabledInput">limit</label>
            <input type="text" class="form-control" name="limit[{{ $jenis == 'edit' ? $item->limit->id : $item->id }}]" id="helpInputTop" value="{{$item->limit->nominal_formatted ?? 0}}"/>
        </div>
    </div>
 @endforeach