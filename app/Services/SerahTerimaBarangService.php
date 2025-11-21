<?php

namespace App\Services;

use App\Models\SerahTerimaBarangDetail;
use App\Models\Penjualan\SerahTerimaBarang;

class SerahTerimaBarangService
{

    public function mappingPenjualanDetail($request, $penjualan)
    {
        $item_penjualan =  [];

        foreach($penjualan as $produk_id =>  $item) {
            $qty = str_replace('.', '', $request->qty[$produk_id]);
            $diskon = str_replace('.', '', $request->nominal_diskon[$produk_id]);
            $harga_satuan = str_replace('.', '', $request->harga_satuan[$produk_id]);

            $item_penjualan[$produk_id] = new SerahTerimaBarangDetail([
                'produk_id' => $produk_id,
                'qty' => $qty,
                'harga' => $harga_satuan,
                'diskon' => $diskon,
                'grand_total' => ($harga_satuan - $diskon) * $qty
            ]);
        }

        return compact('item_penjualan');
    }


    public function mappingListSerahTerima($data)
    {
        if ($data instanceof SerahTerimaBarang) {
            $data->load(['penjualan' => function ($query) {
                $query->selesaiSerahTerima(false);
            }]);
            return [
                'id_penjualan' => $data->id,
                'nomor_penjualan' => $data->nomor,
                'nama_karyawan' => $data->karyawan->nama,  
                'tanggal_penerimaan' => $data->tanggal_penerimaan_formatted ?? null,  
                'harga' => $data->harga_satuan,
                'total' => $data->total_formatted,
                'diskon' => $data->diskon,
                'grand_total' => $data->grand_total,
                'item_penjualan' => $data->serahTerimaDetail
            ];

        }

        $row = '';
        foreach ($data as $item) {
            $row .= '<tr data-dismiss="modal" class="cursor-pointer">
                <td class="hidden" data-target="id_penjualan">' . $item->id . '</td>
                <td data-target="tanggal_penerimaan" class="hidden">' . $item->tanggal_penerimaan_formatted . '</td>
                <td data-target="nomor_penjualan">' . $item->nomor . '</td>
                <td data-target="nama_karyawan">' . $item->karyawan->nama . '</td>
                </tr>';
        }

        return $row;
    }

}