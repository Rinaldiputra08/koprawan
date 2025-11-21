<?php

namespace App\Repositories;

use App\Models\MasterData\Produk;
use App\Models\Penjualan\Penjualan;
use Mavinoo\Batch\BatchFacade;

class ReturPenjualanRepository
{

    public function getTransById($id, $column = null)
    {
        return Penjualan::where($column ?? 'id', $id)->first();
    }

    public function getTrans($search = null)
    {
        return Penjualan::where(function ($query) use ($search) {
            if ($search) {
                $query->where('nomor', 'like', "%{$search}%")
                    ->where('nama', 'like', "%{$search}%");
            }
        })
            ->doesntHave('retur')
            ->whereMonth('tanggal', '<', now()->format('m'))
            ->where('batal', 0)
            ->limit(10)->get();
    }

    public function getListedProduk(array $id)
    {
        return Produk::with('diskon')->whereIn('id', $id)->get();
    }

    public function updateStokProduk($data_update)
    {
        return BatchFacade::update(new Produk(), $data_update, 'id');
    }
}
