<?php

namespace App\Services;

use App\Models\MasterData\Produk;

class ProdukService
{
    public function mappingListProduk($data)
    {
        if ($data instanceof Produk) {
            return [
                'id_produk' => $data->id,
                'kode_produk' => $data->kode,
                'nama_produk' => $data->nama,
                'harga_satuan' => $data->harga_beli_formatted,
                'stok' => $data->stock_free
            ];
        }

        $row = '';
        foreach ($data as $item) {
            $row .= '<tr data-dismiss="modal" class="cursor-pointer">
                <td class="hidden" data-target="id_produk">' . $item->id . '</td>
                <td data-target="kode_produk">' . $item->kode . '</td>
                <td data-target="nama_produk">' . $item->nama . '</td>
                <td data-target="harga_satuan">' . $item->harga_beli_formatted . '</td>
                <td data-target="stok">' . $item->stock_free . '</td>
                </tr>';
        }

        return $row;
    }
}
