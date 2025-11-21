<?php

namespace App\Services;

use App\Models\MasterData\Karyawan;

class VoucherService
{
    public function mappingListUser($data)
    {
        $row = '';
        foreach ($data as $item) {
            $row .= 'data-dismiss="modal" data-id="' . $item->id . '" class="cursor-pointer"
                <td class="hidden" data-target="id">' . $item->id . '</td>
                <td data-target="nik">' . $item->nik . '</td>
                <td data-target="nama">' . $item->nama . '</td>
                <td data-target="total_transaksi">' . ($item->piutang->nominal_formatted ?? 0) . '</td>
                </tr>';
        }

        return $row;
    }
}
