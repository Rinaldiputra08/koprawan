<?php 
namespace App\Repositories;


use App\Models\MasterData\VoucherKriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class VoucherKriteriaRepository 
{
    public function store(Request $request)
    {
        return VoucherKriteria::create([
            'nama' => $request->harga_beli,
            'nominal' => $request->nominal,
            'user_id' => $request->user()->id,
            'tanggal' => $request->tanggal,
        ]);
    }

}