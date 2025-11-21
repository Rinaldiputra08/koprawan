<?php

namespace App\Services;

use App\Helpers\ResponseException;
use App\Models\MasterData\Produk;
use App\Models\Penjualan\PemakaianVoucher;
use App\Repositories\PenjualanLangsungRepository;
use Illuminate\Http\Request;

class PenjualanLangsungService
{
    public function transVoucher(Request $request, PenjualanLangsungRepository $repository)
    {
        $trans_voucher = [];
        $vouchers = $repository->getVoucher($request->voucher);
        foreach ($vouchers as $voucher) {
            // check if voucher is already used or not
            if ($voucher->ketentuan) {
                foreach ($voucher->kriteria as $kriteria) {
                    $pemakaian_voucher = $voucher->pemakaian->filter(function ($item) {
                        return $item->karyawan_id == request()->user()->id;
                    });

                    if ($kriteria->nama == 'maksimal pemakaian' && $pemakaian_voucher->count() >= $kriteria->nominal) {
                        $voucher_used = $pemakaian_voucher;
                    }
                }
            } else {
                $pemakaian_voucher = $voucher->pemakaian->filter(function ($item) {
                    return $item->karyawan_id == request()->user()->id;
                });

                if ($pemakaian_voucher->count() > 0) {
                    $voucher_used = $pemakaian_voucher;
                }
            }
            if (isset($voucher_used)) {
                throw new ResponseException('Voucher sudah digunakan', $voucher_used->values());
            }
            // data voucher
            $trans_voucher[] = new PemakaianVoucher([
                'voucher_id' => $voucher->id,
                'nama' => $voucher->nama,
                'nominal' => $voucher->nominal,
                'karyawan_id' => $request->karyawan_id,
            ]);
            $request->grand_total -= $voucher->nominal;
        }
        return $trans_voucher;
    }

    public function periodeTransaksi()
    {
        $tanggal_closing = getConfig('tanggal_closing_transaksi');
        $periode = date('Ym');
        if ((int) date('d') < (int)$tanggal_closing) {
            $periode -= 1;
        }
        $tanggal_awal = dateFormat($periode . $tanggal_closing, 'Y-m-d');
        if ((int) date('d') >= (int) $tanggal_closing) {
            $periode += 1;
        }
        $tanggal_akhir = dateFormat($periode . $tanggal_closing, 'Y-m-d');

        return compact('tanggal_awal', 'tanggal_akhir');
    }

    public function dataVoucher($karyawan, $vouchers, $pemakaian)
    {
        $list_voucher = [];
        $vouchers = $vouchers->merge($karyawan->voucher);
        // foreach ($karyawan->voucher as $voucher) {
        //     $vouchers->push($voucher);
        // }
        foreach ($vouchers as $voucher) {
            if ($voucher->ketentuan) {
                $maksimal_pakai = $voucher->kriteria->where('nama', 'maksimal pemakaian')->first();
                if ($maksimal_pakai) {
                    $pemakaian_terakhir = $pemakaian->filter(function ($item) use ($voucher) {
                        return $item->tanggal >= $voucher->tanggal_awal && $item->tanggal <= $voucher->tanggal_akhir && $item->voucher_id == $voucher->id;
                    })->count();
                    $maksimal_pakai = $maksimal_pakai->nominal;
                    if ($pemakaian_terakhir >= $maksimal_pakai) {
                        continue;
                    }
                }
            }
            $list_voucher[] = $voucher;
        }

        return $list_voucher;
    }

    public function mappingListProduk($data, $jenis)
    {
        // dd($data);
        if ($data instanceof Produk) {

            return [
                'id_produk' => $data->id,
                'kode_produk' => $data->kode,
                'nama_produk' => $data->nama,
                'harga_satuan' => $data->harga_jual_formatted,
                'diskon' => $data->diskon->nominal ?? 0,
                'stok' => $data->stock_free,
                'jenis' => $jenis
            ];
        }

        $row = '';
        foreach ($data as $item) {
            $row .= '<tr data-dismiss="modal" class="cursor-pointer">
                <td class="hidden" data-target="id_produk">' . $item->id . '</td>
                <td class="hidden" data-target="jenis">' . $jenis . '</td>
                <td data-target="kode_produk">' . $item->kode . '</td>
                <td data-target="nama_produk">' . $item->nama . '</td>      
                <td data-target="harga_satuan">' . $item->harga_jual_formatted . '</td>
                <td data-target="diskon">' . ($item->diskon->nominal ?? 0) . '</td>
                <td data-target="stok">' . $item->stock_free . '</td>
                </tr>';
        }

        return $row;
    }
}
