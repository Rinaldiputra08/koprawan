<?php

namespace App\Repositories;
use Illuminate\Support\Str;
use Mavinoo\Batch\BatchFacade;
use App\Models\MasterData\Produk;
use App\Models\Penjualan\Penjualan;
use App\Models\Penjualan\PenjualanDetail;
use Illuminate\Database\Eloquent\Builder;

use App\Models\Penjualan\SerahTerimaBarang;

class SerahTerimaBarangRepository
{
    public function getPenjualanById($id, $column = null)
    {
        return SerahTerimaBarang::with(['penjualanDetail.produk', 'karyawan:id,nama'])->where($column ?? 'id', $id)->first();
    }

    public function getListedPenjualan(array $id)
    {
        return Penjualan::whereIn('id', $id)->get();
    }    

    public function store($request, $item_penjualan)
    {
        $stBarang = SerahTerimaBarang::create([
            'nomor' => numbering((new SerahTerimaBarang())->getTable(), 'nomor', 'STB' . date('ym')),
            'karyawan_id' => $request->karyawan_id,
            'penjualan_id' => $request->penjualan_id ?? null,
            'total' => collect($item_penjualan)->sum('grand_total'),
            'grand_total' => collect($item_penjualan)->sum('grand_total'),
            'tanggal_penerimaan' => date('Y-m-d'),
            'user_id' => $request->user()->id,
            'user_input' => $request->user()->name,
        ]);

        $stBarang->serahTerimaDetail()->saveMany($item_penjualan);
        $stBarang->refresh();

        return $stBarang;
    }

    public function getPenjualan($search = null)
    {
        return Penjualan::doesntHave('serahTerima')
            ->with('penjualanDetail.produk', 'karyawan:id,nama')
            ->whereIn('jenis', ['online'])
            ->where(function ($query) use ($search) {
                if ($search) {
                    $query->where('nomor', 'like', "%{$search}%")
                        ->orWhereHas('karyawan', function ($query) use ($search) {
                            $query->where('nama', 'like', "%{$search}%");
                        });
                    ;
                }
            })->limit(10)->get();
    } 

}