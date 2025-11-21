<?php

namespace App\Services;

use App\Models\Closing\ClosingProduk;
use App\Models\MasterData\Produk;

class ClosingProdukService
{
    public function data($tahun, $bulan, $store = false)
    {
        $data = [];
        $closingsebelumnya = ClosingProduk::where('periode', ($tahun . $bulan) - 1)->get()->keyBy('produk_id');
        $produk = Produk::with([
            'penjualan' => function ($query) use ($bulan, $tahun) {
                $query->whereHas('penjualan', function ($query) use ($bulan, $tahun) {
                    $query->batal(false)
                        ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
                });
            },
            'penerimaan' => function ($query) use ($bulan, $tahun) {
                $query->whereHas('penerimaan', function ($query) use ($bulan, $tahun) {
                    $query->batal(false)
                        ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
                });
            }
        ])->get();

        foreach ($produk as $item) {
            $qty_awal = $closingsebelumnya[$item->id]->qty_akhir ?? 0;
            $qty_masuk = $item->penerimaan->sum('qty');
            $qty_keluar = $item->penjualan->sum('qty');
            $hpp_akhir = $item->penjualan->last()->hpp ?? 0;
            $qty_akhir =  $qty_awal + $qty_masuk - $qty_keluar;
            $data_akhir =
                [
                    'periode' => $tahun . $bulan,
                    'produk_id' => $item->id,
                    'qty_awal' => $qty_awal,
                    'hpp_awal' => $closingsebelumnya[$item->id]->hpp ?? 0,
                    'qty_masuk' => $qty_masuk,
                    'qty_keluar' => $qty_keluar,
                    'hpp_akhir' => $hpp_akhir,
                    'qty_akhir' => $qty_akhir,
                    'amount_akhir' => $hpp_akhir * $qty_akhir,
                ];
            if (!$store) {
                $data_akhir['nama_produk'] = $item->nama;
            }
            $data[] = $data_akhir;
        }

        return $data;
    }
}
