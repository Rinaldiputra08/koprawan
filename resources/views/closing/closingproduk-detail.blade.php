<div class="row">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Periode</th>
                    <th>Produk</th>
                    <th>Qty Awal</th>
                    <th>Hpp Awal</th>
                    <th>Qty Masuk</th>
                    <th>Qty Keluar</th>
                    <th>Hpp Akhir</th>
                    <th>Qty Akhir</th>
                    <th>Amount Akhir</th>
        
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach ($data as $item)          
                <tr>
                    <td>{{$no++}}</td>
                    <td>{{ dateFormat($item['periode'].'01', 'm-Y') }}</td>
                    <td>{{$item['nama_produk']}}</td>
                    <td>{{$item['qty_awal']}}</td>
                    <td>{{$item['hpp_awal']}}</td>
                    <td>{{$item['qty_masuk']}}</td>
                    <td>{{$item['qty_keluar']}}</td>
                    <td>{{$item['hpp_akhir']}}</td>
                    <td>{{$item['qty_akhir']}}</td>
                    <td>{{$item['amount_akhir']}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="row mt-4 ml-7">
            <div class="col ml-2">
                    <button type="submit" class="btn btn-primary simpan">Simpan</button>        
                @isset($approval)         
                    @if ($approval->proses == 0)          
                        <button type="submit" class="btn btn-success approve">Approve</button>
                    @elseif ($approval->approve == 1)
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-danger">
                                    <div class="alert-body">Data closing Periode ini sudah di approve oleh {{$approval->user_approval}}</div>
                                </div> 
                            </div> 
                        </div>
                    @endif
                @endisset
            </div>
        </div>

    </div>
</div>
