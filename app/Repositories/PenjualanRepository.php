<?php

namespace App\Repositories;

use App\Models\MasterData\Produk;
use App\Models\MasterData\Voucher;
use App\Models\Penjualan\Cart;
use App\Models\Penjualan\Penjualan;
use Illuminate\Support\Collection;
use Mavinoo\Batch\BatchFacade;

class PenjualanRepository
{
    public function getVoucher($ids)
    {
        return Voucher::with('pemakaian', 'kriteria')->whereIn('id', $ids)->get();
    }

    public function updateStokProduk($data_update)
    {
        return BatchFacade::update(new Produk(), $data_update, 'id');
    }

    public function updateCart(Collection $data_cart)
    {
        return Cart::whereIn('id', $data_cart->pluck('id'))->update(['terjual' => 1]);
    }

    public function show($id)
    {
        return Penjualan::with('penjualanDetail.produk:id,judul,nama','voucher')->select('id','nomor','total','diskon','grand_total','tanggal')->where('id', $id)->where('karyawan_id', request()->user()->id)->first();
    }

    public function store($request)
    {

        $penjualan = Penjualan::create([
            'nomor' => numbering((new Penjualan())->getTable(), 'nomor', 'PP' . date('ym')),
            'karyawan_id' => $request->karyawan_id,
            'total' => $request->total_harga,
            'diskon' => $request->diskon,
            'grand_total' => $request->grand_total,
            'status' => 'Pesan',
            'jenis' => 'Online',
            'user_id' => null,
            'user_input' => $request->user()->nama,
        ]);

        return $penjualan;
    }
}
