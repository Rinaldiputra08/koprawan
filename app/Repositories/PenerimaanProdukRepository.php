<?php

namespace App\Repositories;

use App\Models\MasterData\Produk;
use App\Models\MasterData\Supplier;
use App\Models\Pembelian\PemesananProduk;
use App\Models\Pembelian\PemesananProdukDetail;
use App\Models\Pembelian\PenerimaanProduk;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Mavinoo\Batch\BatchFacade;

class PenerimaanProdukRepository
{
    public function getSupplier()
    {
        return Supplier::active()->get();
    }

    public function getProdukById($id, $column = null)
    {
        return Produk::active()->where($column ?? 'id', $id)->first();
    }

    public function getListedProduk(array $id)
    {
        return Produk::whereIn('id', $id)->get();
    }

    public function getPenerimaan($search = null)
    {
        return PenerimaanProduk::with('supplier:id,nama')
            ->where(function (Builder $query) use ($search) {
                if ($search) {
                    $query->where('nomor', 'like', "%{$search}%")
                        ->orWhereHas('supplier', function ($query) use ($search) {
                            $query->where('nama', 'like', "%{$search}%");
                        });
                }
            })->orderBy('id', 'desc')
            ->limit(10)
            ->get();
    }

    public function getPenerimaanById($id, $column = null)
    {
        return PenerimaanProduk::with(['penerimaanDetail.produk', 'supplier:id,nama'])
            ->where($column ?? 'id', $id)->first();
    }

    public function getPemesananById($id, $column = null)
    {
        return PemesananProduk::with(['pemesananDetail' => function ($query) {
            $query->selesaiPenerimaan(false);
        }])
            ->batal(false)
            ->where($column ?? 'id', $id)->first();
    }

    public function store($request, $item_produk)
    {
        $penerimaan = PenerimaanProduk::create([
            'uuid' => Str::uuid(),
            'nomor' => numbering((new PenerimaanProduk())->getTable(), 'nomor', 'PP' . date('ym')),
            'pemesanan_produk_id' => $request->pemesanan_id ?? null,
            'supplier_id' => $request->supplier,
            'ppn' => 0,
            'total' => collect($item_produk)->sum('sub_total'),
            'tanggal_penerimaan' => $request->tanggal_penerimaan,
            'tanggal_tagihan' => !$request->tagihan ? $request->tanggal_penerimaan : null,
            'nomor_tagihan' => !$request->tagihan ? '-' : null,
            'keterangan' => $request->keterangan,
            'user_id' => $request->user()->id,
            'user_input' => $request->user()->name,
        ]);

        $penerimaan->penerimaanDetail()->saveMany($item_produk);
        $penerimaan->refresh();

        return $penerimaan;
    }

    public function updateStatusPenerimaanDetail($pemesanan_id, $list_produk, $penerimaan = 1)
    {
        return PemesananProdukDetail::where('pemesanan_produk_id', $pemesanan_id)
            ->whereIn('produk_id', $list_produk)
            ->update(['penerimaan' => $penerimaan]);
    }

    public function updateStokProduk($data_update)
    {
        return BatchFacade::update(new Produk(), $data_update, 'id');
    }

    public function batal($request, $penerimaanProduk)
    {
        $penerimaanProduk->tanggal_batal = now();
        $penerimaanProduk->keterangan_batal = $request->keterangan;
        $penerimaanProduk->user_batal_id = $request->user()->id;
        $penerimaanProduk->user_batal = $request->user()->name;
        $penerimaanProduk->save();

        return $penerimaanProduk;
    }
}
