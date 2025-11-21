<?php

namespace App\Repositories;

use App\Models\MasterData\Produk;
use App\Models\MasterData\Supplier;
use App\Models\Pembelian\PemesananProduk;
use App\Models\Pembelian\PenerimaanProduk;
use App\Models\Pembelian\PenerimaanProdukDetail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class PemesananProdukRepository
{
    public function getProduk($search = null)
    {
        return Produk::active()
            ->where(function (Builder $query) use ($search) {
                if ($search) {
                    $query->where('kode', 'like', "%{$search}%")
                        ->orWhere('nama', 'like', "%{$search}%");
                }
            })->limit(10)->get();
    }

    public function getProdukById($id, $column = null)
    {
        return Produk::active()->where($column ?? 'id', $id)->first();
    }

    public function getPemesanan($search = null, $filter_aktif = false)
    {
        return PemesananProduk::with('supplier')
            ->where(function ($query) use ($search) {
                if ($search) {
                    $query->where('nomor', 'like', "%{$search}%");
                }
            })->orWhereHas('supplier', function ($query) use ($search) {
                if ($search) {
                    $query->where('nama', 'like', "%{$search}%");
                }
            })
            ->where(function ($query) use ($filter_aktif) {
                if ($filter_aktif) {
                    $query->where('penerimaan', 0);
                }
            })
            ->limit(10)
            ->get();
    }

    public function getPemesananById($id, $column = null)
    {
        return PemesananProduk::with(['supplier', 'pemesananDetail.produk'])->where($column ?? 'id', $id)->first();
    }

    public function getSupplier()
    {
        return Supplier::active()->get();
    }

    public function store($request, $item_produk)
    {
        $pemesanan = PemesananProduk::create([
            'uuid' => Str::uuid(),
            'nomor' => numbering((new PemesananProduk())->getTable(), 'nomor', 'OP' . date('ym')),
            'supplier_id' => $request->supplier,
            'ppn' => 0,
            'total' => collect($item_produk)->sum('sub_total'),
            'tanggal_pemesanan' => $request->tanggal_pemesanan,
            'keterangan' => $request->keterangan,
            'user_id' => $request->user()->id,
            'user_input' => $request->user()->name
        ]);

        $pemesanan->pemesananDetail()->saveMany($item_produk);

        return $pemesanan;
    }

    public function batal($request, $pemesananProduk)
    {
        $pemesananProduk->tanggal_batal = now();
        $pemesananProduk->keterangan_batal = $request->keterangan;
        $pemesananProduk->user_batal_id = $request->user()->id;
        $pemesananProduk->user_batal = $request->user()->name;
        $pemesananProduk->save();

        return $pemesananProduk;
    }
}
