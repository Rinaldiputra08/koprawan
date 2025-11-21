<?php

namespace App\Services;

use App\Models\Pembelian\PenerimaanProduk;
use App\Models\Pembelian\PenerimaanProdukDetail;

class PenerimaanProdukService
{
    public function mappingProdukDetail($request, $produk)
    {
        $item_produk = [];
        $update_stok = [];
        foreach ($produk as $produk_id => $item) {
            $qty = str_replace('.', '', $request->qty[$produk_id]);
            $diskon = str_replace('.', '', $request->nominal_diskon[$produk_id]);
            $harga_satuan = str_replace('.', '', $request->harga_satuan[$produk_id]);

            $hpp = (($item->stock_free * $item->hpp) + ($qty * $harga_satuan)) / ($qty + $item->stock_free);

            $item_produk[$produk_id] = new PenerimaanProdukDetail([
                'produk_id' => $produk_id,
                'qty' => $qty,
                'harga' => $harga_satuan,
                'diskon' => $diskon,
                'sub_total' => ($harga_satuan - $diskon) * $qty
            ]);

            $update_stok[] = [
                'id' => $produk_id,
                'harga_beli' => $harga_satuan,
                'hpp' => $hpp,
                'hpp_sebelum' => $item->hpp,
                'stock_fisik' => $item->stock_fisik + $qty,
                'stock_free' => $item->stock_free + $qty
            ];
        }

        return compact('item_produk', 'update_stok');
    }

    public function mappingListPenerimaan($data)
    {
        if ($data instanceof PenerimaanProduk) {
            $data->load(['pemesanan' => function ($query) {
                $query->selesaiPenerimaan(false);
            }]);
            return [
                'nomor_penerimaan' => $data->nomor,
                'supplier' => $data->supplier->id,
                'tanggal_penerimaan' => $data->tanggal_penerimaan_formatted,
                'keterangan' => $data->keterangan,
                'pemesanan' => $data->pemesanan,
                'tanggal_pemesanan' => $data->pemesanan->tanggal_pemesanan_formatted ?? null,
                'nomor_tagihan' => $data->nomor_tagihan,
                'total' => $data->total_formatted,
                'ppn' => $data->ppn_formatted,
                'grand_total' => numberFormat($data->total + $data->ppn),
                'item_produk' => $data->penerimaanDetail
            ];
        }

        $row = '';
        foreach ($data as $item) {
            $row .= '<tr data-dismiss="modal" class="cursor-pointer">
                <td data-target="nomor_penerimaan">' . $item->nomor . '</td>
                <td data-target="tanggal_penerimaan" class="hidden">' . $item->tanggal_penerimaan_formatted . '</td>
                <td data-target="supplier" class="hidden">' . $item->supplier->id . '</td>
                <td>' . $item->supplier->nama . '</td>
                <td>' . $item->total_formatted . '</td>
                <td>' . $item->ppn_formatted . '</td>
                <td>' . numberFormat($item->total + $item->ppn) . '</td>
                </tr>';
        }

        if ($row == '') {
            $row = '<tr class="text-center"><td colspan="5"><small class="text-danger">Data penerimaan tidak ditemukan</small></td></tr>';
        }

        return $row;
    }
}
