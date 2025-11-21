<?php

namespace App\Repositories;
use App\Models\Gudang\BeritaAcaraGudang;
use App\Models\Gudang\BeritaAcaraGudangDetail;
use App\Models\MasterData\Produk;
use Mavinoo\Batch\BatchFacade;

class BeritaAcaraGudangRepository

{
    public function store($request, $item_produk)
    {
        $pemesanan = BeritaAcaraGudang::create([
            'nomor' => numbering((new BeritaAcaraGudang())->getTable(), 'nomor', 'BAG' . date('ym')),
            'jenis' => $request->jenis,
            'tanggal_berita_acara' => convertDate($request->tanggal_berita_acara),
            'keterangan' => $request->keterangan,
            'user_id' => $request->user()->id,
            'user_input' => $request->user()->name
        ]);

        $pemesanan->beritaAcaraGudangDetail()->saveMany($item_produk);

        return $pemesanan;
    }

    public function updateStokProduk($data_update)
    {
        return BatchFacade::update(new Produk(), $data_update, 'id');
    }

    public function batal($request, $beritaAcaraGudang)
    {
        $beritaAcaraGudang->tanggal_batal = now();
        $beritaAcaraGudang->keterangan_batal = $request->keterangan;
        $beritaAcaraGudang->user_batal_id = $request->user()->id;
        $beritaAcaraGudang->user_batal = $request->user()->name;
        $beritaAcaraGudang->save();

        return $beritaAcaraGudang;
    }

    public function getListedProduk(array $id)
    {
        return Produk::whereIn('id', $id)->get();
    }
}
