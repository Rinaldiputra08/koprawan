<?php

namespace App\Services;

use App\Models\Pembelian\PemesananProduk;

class PemesananProdukService
{
    public function mappingListPemesanan($data)
    {
        if ($data instanceof PemesananProduk) {
            return [
                'nomor_pemesanan' => $data->nomor,
                'supplier' => $data->supplier->id,
                'tanggal_pemesanan' => $data->tanggal_pemesanan_formatted,
                'keterangan' => $data->keterangan,
                'total' => $data->total_formatted,
                'ppn' => $data->ppn_formatted,
                'grand_total' => numberFormat($data->total + $data->ppn),
                'item_produk' => $data->pemesananDetail
            ];
        }

        $row = '';
        foreach ($data as $item) {
            $row .= '<tr data-dismiss="modal" class="cursor-pointer">
                <td data-target="nomor_pemesanan">' . $item->nomor . '</td>
                <td data-target="tanggal_pemesanan" class="hidden">' . $item->tanggal_pemesanan_formatted . '</td>
                <td data-target="supplier" class="hidden">' . $item->supplier->id . '</td>
                <td>' . $item->supplier->nama . '</td>
                <td>' . $item->total_formatted . '</td>
                <td>' . $item->ppn_formatted . '</td>
                <td>' . numberFormat($item->total + $item->ppn) . '</td>
                </tr>';
        }

        if ($row == '') {
            $row = '<tr class="text-center"><td colspan="5"><small class="text-danger">Data pemesanan tidak ditemukan</small></td></tr>';
        }

        return $row;
    }
}
